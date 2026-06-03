<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriItemsController extends Controller
{
    public function index()
    {
        return view('kategori_items.index.index');
    }

    public function search(Request $request)
    {
        $kode = $request->kode;
        $nama = $request->nama;

        $data_search = Kategori::query();

        if (!empty($kode)) $data_search = $data_search->where('kode', $kode);
        if (!empty($nama)) $data_search = $data_search->where('nama', 'LIKE', '%' . $nama . '%');

        $data_search = $data_search->select('kode', 'nama')->orderBy('id')->get();

        return response()->json([
            'status' => 200,
            'data' => $data_search
        ]);
    }

    public function formView($method, $id = 0)
    {
        if ($method == 'new') {
            $kategori = null;
        } else {
            $kategori = Kategori::find($id);
        }
        $data['item'] = $kategori; // Use 'item' to match layout pattern
        $data['method'] = $method;
        return view('kategori_items.form.index', $data);
    }

    public function singleView($kode)
    {
        $data['data'] = Kategori::where('kode', $kode)->with('masterItems')->firstOrFail();
        return view('kategori_items.single.index', $data);
    }

    public function formSubmit(Request $request, $method, $id = 0)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        if ($method == 'new') {
            $kategori = new Kategori;
            $kode = Kategori::count('id') + 1;
            $kode = 'K' . str_pad($kode, 4, '0', STR_PAD_LEFT); // Example: K0001
        } else {
            $kategori = Kategori::findOrFail($id);
            $kode = $kategori->kode;
        }

        $kategori->nama = $request->nama;
        $kategori->kode = $kode;
        $kategori->save();

        return redirect('kategori-items');
    }

    public function delete($id)
    {
        Kategori::findOrFail($id)->delete();
        return redirect('kategori-items');
    }

    public function downloadPdf($kode)
    {
        $kategori = Kategori::where('kode', $kode)->with('masterItems')->firstOrFail();
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.kategori', ['data' => $kategori]);
        
        return $pdf->download('kategori_' . $kategori->kode . '.pdf');
    }
}
