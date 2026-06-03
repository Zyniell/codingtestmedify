<form method="POST" enctype="multipart/form-data">
    @csrf
    @if($method == 'edit')
    <div class="form-group">
        <label>Kode Barang</label>
        <input type="text" class="form-control" name="kode_barang" required readonly value="{{$item->kode ?? ''}}">
    </div>
    @endif

    <div class="form-group">
        <label>Nama</label>
        <input type="text" class="form-control" name="nama" required  value="{{$item->nama ?? ''}}">
    </div>

    <div class="form-group">
        <label>Harga Beli</label>
        <input type="number" class="form-control" name="harga_beli" required  value="{{$item->harga_beli ?? ''}}">
    </div>

    <div class="form-group">
        <label>Laba (dalam persen)</label>
        <input type="number" class="form-control" name="laba" required  value="{{$item->laba ?? ''}}">
    </div>

    @php $selected = $item->supplier ?? ''; @endphp
    <div class="form-group">
        <label>Supplier</label>
        <select class="form-control" required name="supplier">
            <option @if($selected == '') selected @endif value="">--Pilih--</option>
            <option @if($selected == 'Tokopaedi') selected @endif>Tokopaedi</option>
            <option @if($selected == 'Bukulapuk') selected @endif>Bukulapuk</option>
            <option @if($selected == 'TokoBagas') selected @endif>TokoBagas</option>
            <option @if($selected == 'E Commurz') selected @endif>E Commurz</option>
            <option @if($selected == 'Blublu') selected @endif>Blublu</option>
        </select>
    </div>

    @php $selected = $item->jenis ?? ''; @endphp
    <div class="form-group">
        <label>Jenis</label>
        <select class="form-control" required name="jenis">
            <option @if($selected == '') selected @endif value="">--Pilih--</option>
            <option @if($selected == 'Obat') selected @endif>Obat</option>
            <option @if($selected == 'Alkes') selected @endif>Alkes</option>
            <option @if($selected == 'Matkes') selected @endif>Matkes</option>
            <option @if($selected == 'Umum') selected @endif>Umum</option>
            <option @if($selected == 'ATK') selected @endif>ATK</option>
        </select>
    </div>

    <div class="form-group mt-3">
        <label>Kategori</label>
        <div class="card p-3" style="max-height: 180px; overflow-y: auto; background-color: #f8f9fa; border: 1px solid #ced4da;">
            @if(isset($categories) && $categories->count() > 0)
                <div class="row">
                    @foreach($categories as $category)
                        <div class="col-md-6 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="categories[]" value="{{ $category->id }}" id="category-{{ $category->id }}"
                                    @if(isset($item) && $item->kategoris->contains($category->id)) checked @endif>
                                <label class="form-check-label" for="category-{{ $category->id }}">
                                    {{ $category->nama }} ({{ $category->kode }})
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-muted mb-0"><small>Belum ada kategori. Silakan buat kategori terlebih dahulu.</small></p>
            @endif
        </div>
    </div>

    <div class="form-group mt-3">
        <label>Foto Barang</label>
        <input type="file" class="form-control" name="foto" accept="image/*">
        @if(isset($item->foto) && $item->foto)
            <div class="mt-2">
                <p class="mb-1 text-muted"><small>Foto Saat Ini:</small></p>
                <img src="{{ asset('storage/' . $item->foto) }}" alt="Foto Item" style="max-height: 150px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border: 1px solid #ddd;">
            </div>
        @endif
    </div>

    <button class="btn btn-primary mt-3">Submit</button>

</form>