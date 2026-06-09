# ZooTrack - Hayvanat Bahçesi Hayvan Takip Sistemi

ZooTrack, Hayvanat Bahçesi yöneticilerinin ve bakıcılarının, hayvanların envanterini, sağlık durumlarını, kaldıkları kafesleri ve beslenme planlarını takip etmesini sağlayan modern, web tabanlı bir otomasyon sistemidir.

## Proje Özellikleri

- **Güvenli Kullanıcı Kayıt & Giriş**: Şifreler `password_hash()` (bcrypt) ile şifrelenir ve oturum yönetimi PHP `sessions` ile gerçekleştirilir.
- **Dinamik İstatistik Dashboard'u**: Toplam kayıtlı hayvan, sağlıklı, hasta, tedavi altındaki ve karantinadaki hayvan sayıları dinamik kartlarda gösterilir.
- **Kapsamlı Hayvan CRUD İşlemleri**:
  - **Hayvan Ekleme**: İsim, tür, yaş, cinsiyet, kafes no, sağlık durumu, beslenme takvimi, notlar ve opsiyonel görsel yükleme.
  - **Hayvan Listeleme & Filtreleme**: Tüm hayvanların grid kartlar halinde listelenmesi. Tür, sağlık durumu ve metin arama (isim/tür/kafes) ile anlık filtreleme.
  - **Hayvan Güncelleme**: Bilgilerin ve görselin güncellenmesi (eski görseller sunucudan otomatik silinir).
  - **Hayvan Silme**: Güvenli silme onay modalı ve kayıtla ilişkili görselin sunucudan otomatik temizlenmesi.
- **Responsive Tasarım**: Bootstrap 5 ve özel CSS ile her ekran boyutu (mobil, tablet, masaüstü) ile uyumlu, modern zümrüt yeşili doğa temalı arayüz (Glassmorphism kartları).

## Kullanılan Teknolojiler

- **Backend**: Pure PHP (Yalın PHP 8.x) - Herhangi bir harici kütüphane veya framework kullanılmamıştır.
- **Database**: MySQL / MariaDB (PDO Sürücüsü)
- **Frontend**: HTML5, Vanilla CSS3 (Kişiselleştirilmiş Tasarım), Bootstrap 5 (Styling kütüphanesi), Bootstrap Icons

## Kurulum ve Çalıştırma

1. Proje dosyalarını yerel web sunucunuzun (XAMPP, WampServer vb.) `htdocs` veya `www` klasörüne (örneğin `/zootrack` adında bir alt klasöre) kopyalayın.
2. MySQL sunucunuzun çalıştığından emin olun.
3. `config.php` dosyasını açarak veritabanı bağlantı bilgilerinizi düzenleyin (Varsayılan: `localhost`, kullanıcı `root`, şifre boş).
4. Tarayıcınızda `http://localhost/zootrack/login.php` adresine gidin. Veritabanı (`zootrack_db`) ve tablolar (`users`, `animals`) sisteme ilk girişte otomatik olarak oluşturulacaktır. Dilerseniz manuel kurulum için `db.sql` dosyasını veritabanınızda içe aktarabilirsiniz.

---

## Ekran Görüntüleri

### 1. Giriş Sayfası (Login)
*[Buraya Giriş Sayfası Ekran Görüntüsünü Ekleyiniz]*

### 2. Yönetim Paneli (Dashboard / Liste)
*[Buraya Liste & İstatistik Paneli Ekran Görüntüsünü Ekleyiniz]*

---

## Proje Tanıtım Videosu

Projenin özelliklerini, kod yapısını ve çalışmasını anlatan tanıtım videosuna aşağıdaki bağlantıdan ulaşabilirsiniz:

👉 **[Proje Tanıtım Videosu Bağlantısı (Youtube / Google Drive)]()**
