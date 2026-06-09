# Yapay Zeka Sohbet Geçmişi (AI.md)

Bu dosya, Web Tabanlı Programlama dersinin 2. projesini geliştirirken kullanılan yapay zeka aracı ile olan konuşmaları içermektedir.

---

## Sohbet 1: Başlangıç ve Konu Belirleme

### Kullanıcı:
> kankam selamlar benim web tabanlı programlama dersimin 2.projesi var bu proje bir php projesi ben sana dosyayı atacağım sende bana yardımcı olacaksın hazır mısın

### Yapay Zeka (Antigravity):
> Aleyküm selam kankam! Tabii ki hazırım, projende sana yardımcı olmaktan memnuniyet duyarım.
>
> Workspace klasörünü (`C:\Users\HP\Desktop\webproje2`) kontrol ettim, şu an içi boş görünüyor. Dosyalarını bu klasöre aktarabilir, proje isterlerini (ödev açıklamasını, PDF'ini veya detaylarını) benimle paylaşabilirsin.
>
> Dosyaları yükledikten veya detayları ilettikten sonra hemen incelemeye başlayabiliriz. Nasıl bir PHP projesi yapıyoruz, ödevin konusu nedir?

---

## Sohbet 2: Proje İsterlerinin Alınması ve Planlama

### Kullanıcı:
> *[Proje İsterleri Google Docs Bağlantısı Gönderildi]*

### Yapay Zeka (Antigravity):
> *[Proje isterleri incelendi ve "Botanik Bahçesi Yönetim Sistemi" konusu üzerinden bir plan sunuldu]*

---

## Sohbet 3: Konu Değişikliği (Hayvanat Bahçesi Hayvan Takip Sistemi)

### Kullanıcı:
> kankam iyi ve hoş düşünmüşsün ama konuyu hayvanat bahçesinde hayvan takip sistemi yapalım mı şunlara da uyup hazırlayalaım... *[Kriterler ve Kurallar Listesi]*

### Yapay Zeka (Antigravity):
> Kankam harika bir fikir! Hayvanat Bahçesi Hayvan Takip Sistemi (**ZooTrack**) ödev için çok daha eğlenceli ve görsel olarak zenginleştirebileceğimiz bir konu.
>
> Ödevin kurallarına (Pure PHP, `password_hash()`, Session yönetimi, `.htaccess` yasağı, `AI.md` belgesi ve CRUD işlevleri vb.) tam uyum sağlayacak şekilde planı güncelledim.
>
> Yeni plana ve veritabanı şemasına göz atmak istersen buradan ulaşabilirsin: `implementation_plan.md`
>
> Bu plana onay veriyorsan hemen dosyaları oluşturmaya ve projeyi hayata geçirmeye başlayayım!

### Kullanıcı:
> başlayalım

---

## Sohbet 4: Uygulama Kodlarının Yazılması

### Yapay Zeka (Antigravity):
Projenin gereksinimlerini karşılamak üzere şu dosyalar oluşturulmuştur:
1. **`db.sql`**: Veritabanı şeması ve tabloları (`users`, `animals`).
2. **`config.php`**: Veritabanı bağlantısı, PDO ayarları, otomatik tablo ve veritabanı kurulumu, oturum kontrolü ve filtreleme yardımcıları.
3. **`header.php` & `footer.php`**: Bootstrap 5 entegrasyonu ve session tabanlı menü yönetimini içeren şablon dosyaları.
4. **`style.css`**: Özel koyu zümrüt yeşili doğa/yaşam temalı, modern glassmorphism kartları içeren CSS tasarım sistemi.
5. **`register.php`**: `password_hash()` kullanılan güvenli kullanıcı kayıt sayfası.
6. **`login.php`**: Oturumları (Session) kontrol eden giriş sayfası.
7. **`logout.php`**: Oturumu sonlandırma scripti.
8. **`index.php`**: Hayvanların listelendiği (Read), arama ve filtreleme yapılabildiği, istatistikleri gösteren dashboard.
9. **`add-animal.php`**: Resim yükleme destekli hayvan ekleme sayfası (Create).
10. **`edit-animal.php`**: Kayıt güncelleme sayfası (Update).
11. **`delete-animal.php`**: Görsel temizliği ve veritabanı silme scripti (Delete).

---

## Sohbet 5: Projenin Kriter Kontrolü ve Hata Düzeltme

### Kullanıcı:
> hocanın attığı belgeye kurallara ve puanlama kriterlerine bak bakalım eksiği gediği var mı

### Yapay Zeka (Antigravity):
> Projedeki tüm dosyaları ve gereksinimleri hocanın kriter belgesi ile tek tek karşılaştırarak kontrol ettim kankam.
>
> **Önemli Düzeltme:** Kontrol sırasında `edit-animal.php` dosyasının en sonunda ufak bir kod hatası (footer yerine yanlışlıkla header dahil edilmişti) tespit ettim ve bunu hemen `<?php include 'footer.php'; ?>` şeklinde düzelttim.
>
> Projenin puanlama kriterlerine uyumu şu şekildedir:
> - **Kullanıcı Kaydı:** `register.php` ile tam olarak sağlanıyor. Şifreler `password_hash()` fonksiyonuyla şifreleniyor.
> - **Oturum Açma/Kapama:** `login.php` ve `logout.php` ile tamamen PHP `$_SESSION` oturumları üzerinden kontrol ediliyor.
> - **Bilgi Girişi (Create):** `add-animal.php` üzerinden hayvan bilgileri ve görselleri eklenebiliyor.
> - **Bilgilerin Listelenmesi (Read):** `index.php` ana sayfası ile veriler aranıp filtrelenerek Bootstrap grid yapısında listeleniyor.
> - **Bilgi Güncelleme (Update):** `edit-animal.php` ile tüm detaylar güncellenebiliyor.
> - **Bilgi Silme (Delete):** `delete-animal.php` ile hem veritabanı kaydı hem de sunucuya yüklenmiş olan görsel dosyası güvenli bir şekilde siliniyor.
> - **Hazır CSS Kütüphanesi:** Bootstrap 5.3.2 ve Bootstrap Icons kullanılarak şık ve modern bir arayüz tasarlandı.
> - **Readme.md Açıklaması:** Kurulum detayları, özellikler ve görsel/video yer tutucularını içeren açıklama dosyası oluşturuldu.
> - **AI.md Dosyası:** Yapay zeka ile yapılan konuşmaların tümü bu dosyada markdown formatında listelendi.
> - **Canlıya Alınması:** Canlı sunucuya (hosting) taşırken `config.php` dosyasındaki veritabanı ayarlarını (DB_HOST, DB_USER, DB_PASS, DB_NAME) sunucu bilgilerine göre güncellemen yeterlidir.
> - **.htaccess Yasağı:** Projede herhangi bir `.htaccess` dosyası kullanılmamaktadır.

