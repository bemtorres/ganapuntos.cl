<?php

namespace App\Http\Controllers;

use App\Models\GP\Usuario;
use App\Models\Rec\Accion;
use App\Models\Rec\Transaccion;
use App\Services\DispatchCredit;
use App\Services\ImportImage;
use App\Services\Jwt\JwtQrDecode;
use App\Services\Policies\UsuarioPolicy;
use Illuminate\Http\Request;

class AccionController extends Controller
{

  private $policy;

  public function __construct() {
    $this->policy = new UsuarioPolicy();
  }

  public function index() {
    $this->policy->admin(current_user());
    $acciones = Accion::get();
    return view('accion.index', compact('acciones'));
  }

  public function create() {
    $this->policy->admin(current_user());
    return view('accion.create');
  }

  public function store(Request $request) {
    $a = new Accion();
    $a->token = substr(md5(time()),8) . time();
    $a->nombre = $request->input('nombre');
    $a->descripcion = $request->input('descripcion');
    $a->credito = $request->input('credito');

    $a->stock_ilimitado = !empty($request->input('cant_stock_swith'));
    $a->stock = $request->input('stock') ?? 0;

    $a->cant_por_usuario_ilimitado = !empty($request->input('cant_per_user_swith'));
    $a->cant_por_usuario = $request->input('cant_per_user') ?? 0;

    $a->id_usuario = current_user()->id;
    $a->estado = $request->input('estado');

    $assets = $a->assets;

    if(!empty($request->file('image'))){
      $request->validate([
        'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
      ]);

      $filename = time() . 'rewards';
      $folder = 'public/gp_images/accion';
      $img = ImportImage::save($request, 'image', $filename, $folder);

      if ($img != 400) {
        $assets['img'] = $img;
      }
    }

    $a->assets = $assets;
    $a->save();

    return redirect()->route('admin.accion.index')->with('success','Se ha creado correctamente');
  }

  public function show($id) {
    $this->policy->admin(current_user());

    $a = Accion::findOrFail($id);

    return view('accion.show', compact('a'));
  }

  public function edit($id) {
    $this->policy->admin(current_user());

    $a = Accion::findOrFail($id);
    return view('accion.edit', compact('a'));
  }

  public function update(Request $request, $id) {
    $this->policy->admin(current_user());

    $a = Accion::findOrFail($id);
    // $a->token = substr(md5(time()),8) . time();
    $a->nombre = $request->input('nombre');
    $a->descripcion = $request->input('descripcion');
    $a->credito = $request->input('credito');

    $a->stock_ilimitado = !empty($request->input('cant_stock_swith'));
    $a->stock = $request->input('stock') ?? 0;

    $a->cant_por_usuario_ilimitado = !empty($request->input('cant_per_user_swith'));
    $a->cant_por_usuario = $request->input('cant_per_user') ?? 0;
    $a->estado = $request->input('estado');

    // $a->id_usuario = current_user()->id;

    $assets = $a->assets;

    if(!empty($request->file('image'))){
      $request->validate([
        'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
      ]);

      $filename = time() . 'rewards';
      $folder = 'public/gp_images/accion';
      $img = ImportImage::save($request, 'image', $filename, $folder);

      if ($img != 400) {
        $assets['img'] = $img;
      }
    }

    $a->assets = $assets;
    $a->update();

    return back()->with('success','Se ha actualizado');
  }

  public function send($id) {
    $this->policy->admin(current_user());

    $a = Accion::findOrFail($id);

    return view('accion.send', compact('a'));
  }

  public function sendQR(Request $request, $id) {
    $this->policy->admin(current_user());

    $a = Accion::findOrFail($id);
    $usuario = (new JwtQrDecode($request->input('jwt')))->call();

    if($usuario instanceof Usuario) {
      $payload = (new DispatchCredit($usuario, $a))->call();

      return back()->with($payload['status'],$payload['message']);
    }

    return back()->with('danger','Error intente nuevamente');
  }

  public function users($id) {
    $this->policy->admin(current_user());

    $a = Accion::findOrFail($id);

    $usuarios = Usuario::with('team')->get();

    return view('accion.users', compact('a','usuarios'));
  }

  public function usersStore(Request $request, $id) {
    $this->policy->admin(current_user());

    $a = Accion::findOrFail($id);
    $usuario = Usuario::findOrFail($request->input('id'));

    if($usuario instanceof Usuario) {
      $payload = (new DispatchCredit($usuario, $a))->call();

      return back()->with($payload['status'],$payload['message']);
    }

    return back()->with('danger','Error intente nuevamente');
  }

  public function historial($id) {
    $this->policy->admin(current_user());

    $a = Accion::findOrFail($id);
    $transacciones = Transaccion::with(['usuario'])->get();

    return view('accion.historial', compact('a','transacciones'));
  }
}
