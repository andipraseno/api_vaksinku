# 📘 ACTASYS HOLDER

-   **Live URL:** `https://holder.actasys.id`
-   **Local URL:** `http://localhost:8001`
-   **Running Dev:** `php artisan serve --port=8001`
-   **Clearing Cache:**<br/>

    > php artisan config:clear<br/>
    > php artisan cache:clear<br/>
    > php artisan route:clear<br/>
    > php artisan view:clear<br/>
    > php artisan clear-compiled<br/>

-   **Install:**<br/>

    > composer install --ignore-platform-reqs<br/>
    > composer dump-autoload<br/>
    > sudo cp -ar ./\_config/. .<br/>
    > npm install --legacy-peer-deps<br/>

---

<br/>
<br/>
# 🔀 Loader

### GET `/loader/software/{id}`

-   **Deskripsi:** Ambil data software berdasarkan id
-   **Token:** ❌

```json
RESPONSE:

success: true,
data: {
  "id": "",
  "nama": "",
  "kode": "",
  "tagline": "",
  "copyright": "",
  "developer": "",
  "versi": "",
}
```

---

### GET `/loader/company/{id}`

-   **Deskripsi:** Ambil data perusahaan berdasarkan id
-   **Auth:** ❌

```json
RESPONSE:

success: true,
data: {
  "id": "",
  "nama": "",
  "kode": "",
  "alamat": "",
  "telepon": "",
  "handphone": "",
  "email": "",
  "website": "",
}
```

<br/>
<br/>

# 🔐 Login

### POST `/loader/login`

-   **Deskripsi:** Login user untuk mendapatkan token.
-   **Auth:** ❌

```json
PARAMETER:

{
  "software_id": "",
  "company_id": "",
  "nama": "",
  "password": ""
}
```

```json
RESPONSE:

success: true,
data:
{
  "user_id": "",
  "user_nama": "",
  "user_email": "",
  "user_handphone": "",
  "access_id": "",
  "access_nama": "",
  "token": "",
  "tab": "",
  "module": "",
}
```

<br/>
<br/>

# 👤 Person

## Agama

### GET `/person/agama`

-   **Deskripsi:** mengambil semua data
-   **Auth:** ✔️

### GET `/person/agama/{id}`

-   **Deskripsi:** mengambil data berdasarkan id
-   **Auth:** ✔️

## Golongan Darah

### GET `/person/golongan_darah`

-   **Deskripsi:** mengambil semua data
-   **Auth:** ✔️

### GET `/person/golongan_darah/{id}`

-   **Deskripsi:** mengambil data berdasarkan id
-   **Auth:** ✔️

## Jenis Kelamin

### GET `/person/jenis_kelamin`

-   **Deskripsi:** mengambil semua data
-   **Auth:** ✔️

### GET `/person/jenis_kelamin/{id}`

-   **Deskripsi:** mengambil data berdasarkan id
-   **Auth:** ✔️

## Hubungan Keluarga

### GET `/person/hubungan_keluarga`

-   **Deskripsi:** mengambil semua data
-   **Auth:** ✔️

### GET `/person/hubungan_keluarga/{id}`

-   **Deskripsi:** mengambil data berdasarkan id
-   **Auth:** ✔️

## Kewarganegaraan

### GET `/person/kewarganegaraan`

-   **Deskripsi:** mengambil semua data
-   **Auth:** ✔️

### GET `/person/kewarganegaraan/{id}`

-   **Deskripsi:** mengambil data berdasarkan id
-   **Auth:** ✔️

## Tingkat Pendidikan

### GET `/person/tingkat_pendidikan`

-   **Deskripsi:** mengambil semua data
-   **Auth:** ✔️

### GET `/person/tingkat_pendidikan/{id}`

-   **Deskripsi:** mengambil data berdasarkan id
-   **Auth:** ✔️

## Jenis SIM

### GET `/person/jenis_sim`

-   **Deskripsi:** mengambil semua data
-   **Auth:** ✔️

### GET `/person/jenis_sim/{id}`

-   **Deskripsi:** mengambil data berdasarkan id
-   **Auth:** ✔️

## Status Perkawinan

### GET `/person/status_perkawinan`

-   **Deskripsi:** mengambil semua data
-   **Auth:** ✔️

### GET `/person/status_perkawinan/{id}`

-   **Deskripsi:** mengambil data berdasarkan id
-   **Auth:** ✔️

## Tipe Pajak

### GET `/person/tipe_pajak`

-   **Deskripsi:** mengambil semua data
-   **Auth:** ✔️

### GET `/person/tipe_pajak/{id}`

-   **Deskripsi:** mengambil data berdasarkan id
-   **Auth:** ✔️

<br/>
<br/>

# 📦 Inventory

## Satuan

### GET `/inventory/satuan`

-   **Deskripsi:** mengambil semua data
-   **Auth:** ✔️

### GET `/inventory/satuan/{id}`

-   **Deskripsi:** mengambil data berdasarkan id
-   **Auth:** ✔️

## Ekspedisi

### GET `/inventory/ekspedisi`

-   **Deskripsi:** mengambil semua data
-   **Auth:** ✔️

### GET `/inventory/ekspedisi/{id}`

-   **Deskripsi:** mengambil data berdasarkan id
-   **Auth:** ✔️

## Jenis Kendaraan

### GET `/inventory/jenis_kendaraan`

-   **Deskripsi:** mengambil semua data
-   **Auth:** ✔️

### GET `/inventory/jenis_kendaraan/{id}`

-   **Deskripsi:** mengambil data berdasarkan id
-   **Auth:** ✔️

<br/>
<br/>

# 💰 Finance

## Valuta

### GET `/finance/valuta`

-   **Deskripsi:** mengambil semua data
-   **Auth:** ✔️

### GET `/finance/valuta/{id}`

-   **Deskripsi:** mengambil data berdasarkan id
-   **Auth:** ✔️

## Bank

### GET `/finance/bank`

-   **Deskripsi:** mengambil semua data
-   **Auth:** ✔️

### GET `/finance/bank/{id}`

-   **Deskripsi:** mengambil data berdasarkan id
-   **Auth:** ✔️

<br/>
<br/>

# 🌍 Area

## Provinsi

### GET `/area/provinsi`

-   **Deskripsi:** mengambil semua data
-   **Auth:** ✔️

### GET `/area/provinsi/{id}`

-   **Deskripsi:** mengambil data berdasarkan id
-   **Auth:** ✔️

## Kota

### GET `/area/kota`

-   **Deskripsi:** mengambil semua data
-   **Auth:** ✔️

### GET `/area/kota/{id}`

-   **Deskripsi:** mengambil data berdasarkan id
-   **Auth:** ✔️

## Kecamatan

### GET `/area/kecamatan`

-   **Deskripsi:** mengambil semua data
-   **Auth:** ✔️

### GET `/area/kecamatan/{id}`

-   **Deskripsi:** mengambil data berdasarkan id
-   **Auth:** ✔️

## Kelurahan

### GET `/area/kelurahan`

-   **Deskripsi:** mengambil semua data
-   **Auth:** ✔️

### GET `/area/kelurahan/{id}`

-   **Deskripsi:** mengambil data berdasarkan id
-   **Auth:** ✔️

<br/>
<br/>

# 🛑 Error Response (Global)

```json
401 Unauthorized
{
  "message": "Unauthenticated."
}

404 Not Found
{
  "message": "Data tidak ditemukan."
}
```
