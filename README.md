# Sistem Keuangan SMP Cendekia Baznas

## Nama Sistem
SCB Keuangan

## Deskripsi Singkat
Dalam mata kuliah Pengembangan Sistem Berorientasi Objek semester ini kami berkesempatan mendapat _client_ dari divisi keuangan SMP Cendekia Baznas. Badan Keuangan SMP Cendekia Baznas memiliki fungsi utama sebagai verificator dalam pengajuan dana dan memonitor aliran keluar-masuk keuangan secara menyeluruh. Dalam teknis pelaksanaannya saat ini divisi keuangan SMP Cendekia Baznas masih menggunakan form tertulis yang terbagi menjadi tiga form yaitu **Fund Request**, **Submission**, dan **Petty Cash** serta input manual menggunakan Microsoft Excel dalam manajemen keuangannya sehingga kami membuat sistem untuk mempermudah hal tersebut.

Sistem kami berbasis website di mana user terkait harus mendaftar akun terlebih dahulu untuk mengelola dan mengajukan dana,  dalam sistem kami telah kami sediakan tiga form pengajuan dana sesuai dengan kebutuhan SMP Cendekia Baznas yaitu **Fund Request**, **Submission**, dan **Petty Cash** di mana setiap form sudah memiliki halaman masing-masing untuk pengajuannya. Setiap detail dari keseluruhan status form pengajuan dana dapat memonitor dan mengatur secara langsung melalui halaman **All Request**, **All Submission**, dan **All Petty** yang selalu terupdate, dalam halaman tersebut juga terdapat pilihan untuk export data secara otomatis menjadi format PDF dan Excel untuk diolah. Agar pengajuan dana dapat disetujui diperlukannya verifikasi dari beberapa stakeholder terkait, stakeholder tersebut memverifikasi menggunakan akun khusus dan menginput tanda tangan online yang sudah difasilitasi oleh sistem dengan _online signature_.

## Ruang Lingkup Pengembangan

### Software
* Visual Studio Code
* PhpStorm
* WebStorm
* PhpMyAdmin

### Hardware
* Intel Core i3
* 4GB RAM

### Tech Stack
* Nuxt.js (Front-end)
* Laravel (Back-end)
* MySQL (DBMS)
* Amazon AMI Linux/Ubuntu EC2 AWS (Server)
* NGINX (Server)
* Apache (Server)

## Diagram (Use Case, Activity, Class)

### Use Case Diagram

### Activity Diagram

### Class Diagram

## Fitur Umum
* Monitoring dan manajemen Form Fund Request
* Monitoring dan manajemen Form Submission
* Monitoring dan manajemen Form Petty Cash
* Monitoring Budget
* Form Verification dengan Online Signature
* Export form dalam format PDF dan Excel

## Konsep OOP yang Digunakan
* Class
* Object
* Abstraction
* Encapsulation
* Inheritance

## Tipe Desain Pengembangan yang Digunakan (Pattern/Anti-Pattern)

### Pattern
1. Abstract Factory

Pada Abstract Factory Pattern, abstract factory akan mengartikan factory konkret mana yang diperlukan untuk membuat sebuah object. Factory yang konkret juga perlu memastikan bahwa objek yang dibuat sudah tetap sesuai dengan yang dibutuhkan.

Sebagai contoh, `DatabaseSeeder` akan dianggap sebagai abstract factory, kemudian `DatabaseSeeder` akan memanggil concrete factory yaitu `FormRequestSeeder`, concrete factory akan membuat objek dari class yang sudah ditentukan yaitu model `FormRequest`.

2. Observer

Observer pattern akan membuat sebuah class yang akan mengawasi setiap perubahan dari suatu object. Class observer akan melakukan sesuatu permintaan klien setiap suatu object yang diawasi melakukan perubahan. 

Observer akan melakukan pengawasan terhadap objek Form Request. Jika Form Request telah berhasil diperbarui, Observer akan memeriksa atribut `is_confirmed_pic`, `is_confirmed_head_dept`, dan sebagainya dari objek tersebut. Jika semua atribut sudah bernilai `1`, maka otomatis observer akan merubah atribut status pada form request menjadi Menunggu Pembayaran ditandai dengan `status_id` bernilai `2`.

3. Facade

Facade design pattern bertujuan untuk menyembunyikan sebuah kompleksitas dari suatu subsystem dimana pemanggilan class terjadi sangat banyak. Dengan menggunakan facade design pattern, sebuah class facade akan menyembunyikan pemanggilan class-class lain yang dibutuhkan oleh subsystem tersebut.

Contoh diambil pada fungsi export PDF dari Form Request. Pada fungsi tersebut terdapat pemanggilan class PDF, sebuah package untuk export PDF pada Laravel yaitu `laravel/barryvdh-dompdf`. Pada class PDF hanya terdapat Facade class berupa fungsi  `_callStatic` yang akan memanggil class-class lain sesuai kebutuhan pengguna.


4. Strategy

Pada Design Pattern Strategy sebuah class melakukan fungsi berdasarkan fungsi pada class lainnya. Hal ini bergantung pada penggunaan oleh klien, class akan diinisiasi berdasarkan kebutuhan yang diperlukan oleh klien.

Pada contoh di atas dapat dilihat bahwa `UploadHelper` akan memilih class `Uploader` lainnya berdasarkan ekstensi dari file yang diupload. Penempatan folder juga berbeda berdasarkan dari ekstensi file nya.


### Anti-Pattern

## Developer dan Job Description

### Project Manager
Yudit Yudiarto

### Front-End
Muhammad Hilmy Haidar
* Page Design and Layout
* Theming
* i18n International Localization
* Validation
* API Integration
* E-Signature

Yudit Yudiarto
* Business Logic
* E-Signature
* Alert
* API Integration

Fahreza Ikhsan Hafiz
* i18n International Localization

Alfian Hamam Akbar
* i18n International Localization

Intan Aida Rahmani
* Page Design and Layout
* Theming
* API Integration

Muhammad Naufal Shidqi

Ahmad Al-Banjaran Couer D'Alene

Bob Yuwono

### Back-end
Muhammad Zulkifli
* Form Request
* User
* Budget Code
* Export PDF

Reyhan Widyatna Harwenda
* Form Submission
* Export Excel

Yudit Yudiarto
* Form Petty Cash

Muhammad Hilmy Haidar
* Rekening
* Layouting Export PDF
