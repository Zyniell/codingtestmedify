<?php

namespace Tests\Feature;

use App\Models\Kategori;
use App\Models\MasterItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KategoriItemsTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_kategori_with_auto_generated_code()
    {
        $response = $this->post('/kategori-items/form/new', [
            'nama' => 'Peralatan Medis',
        ]);

        $response->assertRedirect('/kategori-items');

        $this->assertDatabaseHas('kategoris', [
            'nama' => 'Peralatan Medis',
            'kode' => 'K0001',
        ]);
    }

    public function test_can_filter_kategoris()
    {
        $kategori1 = Kategori::create(['nama' => 'Obat Dalam', 'kode' => 'K0001']);
        $kategori2 = Kategori::create(['nama' => 'Alat Steril', 'kode' => 'K0002']);

        // Search by name
        $response = $this->getJson('/kategori-items/search?nama=Steril');
        $response->assertStatus(200);
        $response->assertJsonFragment(['nama' => 'Alat Steril', 'kode' => 'K0002']);
        $response->assertJsonMissing(['nama' => 'Obat Dalam']);

        // Search by code
        $response = $this->getJson('/kategori-items/search?kode=K0001');
        $response->assertStatus(200);
        $response->assertJsonFragment(['nama' => 'Obat Dalam', 'kode' => 'K0001']);
        $response->assertJsonMissing(['nama' => 'Alat Steril']);
    }

    public function test_kategori_single_view_shows_details_and_related_items()
    {
        $kategori = Kategori::create(['nama' => 'Obat Cair', 'kode' => 'K0001']);
        $item = MasterItem::create([
            'kode' => '00001',
            'nama' => 'Paracetamol Syrup',
            'harga_beli' => 5000,
            'laba' => 10,
            'supplier' => 'Tokopaedi',
            'jenis' => 'Obat',
        ]);

        $kategori->masterItems()->attach($item->id);

        $response = $this->get("/kategori-items/view/{$kategori->kode}");
        $response->assertStatus(200);
        $response->assertSee('Obat Cair');
        $response->assertSee('K0001');
        $response->assertSee('Paracetamol Syrup');
    }

    public function test_can_assign_and_sync_categories_to_master_item()
    {
        $kategori1 = Kategori::create(['nama' => 'Kategori A', 'kode' => 'K0001']);
        $kategori2 = Kategori::create(['nama' => 'Kategori B', 'kode' => 'K0002']);

        // Create Master Item and link to Kategori A
        $response = $this->post('/master-items/form/new', [
            'nama' => 'Test Item',
            'harga_beli' => 1000,
            'laba' => 10,
            'supplier' => 'Tokopaedi',
            'jenis' => 'Obat',
            'categories' => [$kategori1->id],
        ]);

        $response->assertRedirect('/master-items');
        
        $item = MasterItem::latest('id')->first();
        $this->assertTrue($item->kategoris->contains($kategori1->id));
        $this->assertFalse($item->kategoris->contains($kategori2->id));

        // Edit Master Item and sync to Kategori B instead of Kategori A
        $response = $this->post("/master-items/form/edit/{$item->id}", [
            'nama' => 'Updated Item',
            'harga_beli' => 1200,
            'laba' => 15,
            'supplier' => 'Tokopaedi',
            'jenis' => 'Obat',
            'categories' => [$kategori2->id],
        ]);

        $response->assertRedirect('/master-items');
        
        $item->refresh();
        $this->assertFalse($item->kategoris->contains($kategori1->id));
        $this->assertTrue($item->kategoris->contains($kategori2->id));
    }

    public function test_can_download_kategori_pdf()
    {
        $kategori = Kategori::create(['nama' => 'Kategori PDF', 'kode' => 'K0001']);
        
        $response = $this->get("/kategori-items/download-pdf/{$kategori->kode}");
        
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }
}
