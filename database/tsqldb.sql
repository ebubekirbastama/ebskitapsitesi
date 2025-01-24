CREATE DATABASE odev_db;
USE odev_db;
CREATE TABLE kategoriler (
    kategori_id INT AUTO_INCREMENT PRIMARY KEY,
    kategori_adi VARCHAR(255) NOT NULL
);
CREATE TABLE kitaplar (
    kitap_id INT AUTO_INCREMENT PRIMARY KEY,
    kitap_adi VARCHAR(255) NOT NULL,
    kitap_turu INT NOT NULL,  -- Bu sütun kategori_id ile ilişkilendirilecek
    yazar VARCHAR(255) NOT NULL,
    sayfa INT NOT NULL,
    yili INT NOT NULL,
    dili VARCHAR(50) NOT NULL,
    fiyat DECIMAL(10,2) NOT NULL,
    barkod VARCHAR(50) NOT NULL,
    kapak_foto VARCHAR(255) NOT NULL,
    FOREIGN KEY (kitap_turu) REFERENCES kategoriler(kategori_id) ON DELETE CASCADE
);
CREATE TABLE yorumlar (
    yorum_id INT AUTO_INCREMENT PRIMARY KEY,
    kitap_id INT NOT NULL,
    kullanici_adi VARCHAR(255) NOT NULL,
    yorum_metni TEXT NOT NULL,
    puan INT NOT NULL CHECK (puan BETWEEN 1 AND 5), -- Puan kolonu eklendi
    tarih TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (kitap_id) REFERENCES kitaplar(kitap_id) ON DELETE CASCADE
);

CREATE TABLE admin_kullanicilar (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kullanici_adi VARCHAR(255) NOT NULL UNIQUE,
    sifre VARCHAR(255) NOT NULL
);




-- Kategoriler Tablosuna Veri Ekleme
INSERT INTO kategoriler (kategori_adi) VALUES 
('Tarih'),
('Felsefe'),
('Bilim Kurgu'),
('Roman'),
('Kişisel Gelişim'),
('Edebiyat'),
('Biyografi'),
('Sanat'),
('Müzik'),
('Teknoloji'),
('İş Dünyası'),
('Çocuk Kitapları'),
('Sosyoloji'),
('Psikoloji'),
('Klasikler'),
('Aşk'),
('Macera'),
('Hikaye'),
('Yemek Kitapları'),
('Seyahat'),
('Politika');

-- Kitaplar Tablosuna Veri Ekleme
INSERT INTO kitaplar (kitap_adi, kitap_turu, yazar, sayfa, yili, dili, fiyat, barkod, kapak_foto) VALUES
('Tarih Kitabı 1', 1, 'Ali Tarihçi', 300, 2020, 'Türkçe', 40.00, '1234567890123', 'tarih_kitap_1.jpg'),
('Felsefe Kitabı 1', 2, 'Ahmet Felsefeci', 250, 2019, 'Türkçe', 35.00, '1234567890124', 'felsefe_kitap_1.jpg'),
('Bilim Kurgu Kitabı 1', 3, 'Yusuf Bilim', 320, 2021, 'Türkçe', 45.00, '1234567890125', 'bilim_kurgu_kitap_1.jpg'),
('Roman Kitabı 1', 4, 'Mehmet Roman', 280, 2018, 'Türkçe', 50.00, '1234567890126', 'roman_kitap_1.jpg'),
('Kişisel Gelişim Kitabı 1', 5, 'Kemal Kişisel', 220, 2022, 'Türkçe', 60.00, '1234567890127', 'kisisel_gelisim_kitap_1.jpg'),
('Edebiyat Kitabı 1', 6, 'Zeynep Edebiyatçı', 270, 2020, 'Türkçe', 40.00, '1234567890128', 'edebiyat_kitap_1.jpg'),
('Biyografi Kitabı 1', 7, 'Ömer Biyograf', 310, 2019, 'Türkçe', 55.00, '1234567890129', 'biyografi_kitap_1.jpg'),
('Sanat Kitabı 1', 8, 'Ayşe Sanatçı', 200, 2021, 'Türkçe', 70.00, '1234567890130', 'sanat_kitap_1.jpg'),
('Müzik Kitabı 1', 9, 'Ali Müzikçi', 250, 2020, 'Türkçe', 45.00, '1234567890131', 'muzik_kitap_1.jpg'),
('Teknoloji Kitabı 1', 10, 'Mehmet Teknolog', 330, 2022, 'Türkçe', 80.00, '1234567890132', 'teknoloji_kitap_1.jpg'),
('İş Dünyası Kitabı 1', 11, 'İbrahim İşadamı', 310, 2021, 'Türkçe', 65.00, '1234567890133', 'is_dunyasi_kitap_1.jpg'),
('Çocuk Kitapları Kitabı 1', 12, 'Fatma Çocuk', 150, 2022, 'Türkçe', 25.00, '1234567890134', 'cocuk_kitaplari_kitap_1.jpg'),
('Sosyoloji Kitabı 1', 13, 'Hasan Sosyolog', 280, 2021, 'Türkçe', 50.00, '1234567890135', 'sosyoloji_kitap_1.jpg'),
('Psikoloji Kitabı 1', 14, 'Leyla Psikolog', 350, 2020, 'Türkçe', 60.00, '1234567890136', 'psikoloji_kitap_1.jpg'),
('Klasikler Kitabı 1', 15, 'Hüseyin Klasik', 400, 2018, 'Türkçe', 45.00, '1234567890137', 'klasikler_kitap_1.jpg'),
('Aşk Kitabı 1', 16, 'Zeynep Aşık', 280, 2022, 'Türkçe', 40.00, '1234567890138', 'ask_kitap_1.jpg'),
('Macera Kitabı 1', 17, 'Osman Maceracı', 350, 2021, 'Türkçe', 50.00, '1234567890139', 'macera_kitap_1.jpg'),
('Hikaye Kitabı 1', 18, 'Emine Hikayeci', 220, 2020, 'Türkçe', 35.00, '1234567890140', 'hikaye_kitap_1.jpg'),
('Yemek Kitapları Kitabı 1', 19, 'Ayşegül Şef', 180, 2021, 'Türkçe', 30.00, '1234567890141', 'yemek_kitaplari_kitap_1.jpg'),
('Seyahat Kitabı 1', 20, 'Mustafa Seyahatçi', 250, 2020, 'Türkçe', 40.00, '1234567890142', 'seyahat_kitap_1.jpg'),
('Politika Kitabı 1', 21, 'Ali Politikacı', 300, 2021, 'Türkçe', 55.00, '1234567890143', 'politika_kitap_1.jpg');

-- Yorumlar Tablosuna Veri Ekleme
INSERT INTO yorumlar (kitap_id, kullanici_adi, yorum_metni, puan, tarih) VALUES
(1, 'Ahmet Yazar', 'Tarih kitabı çok bilgilendiriciydi, çok beğendim.', 5, '2024-12-18 10:00:00'),
(2, 'Murat Okur', 'Felsefe kitabı genel olarak iyi, ancak biraz daha derinlemesine olabilirdi.', 4, '2024-12-18 11:00:00'),
(3, 'Ayşe Bilimsever', 'Bilim kurgu kitabı harikaydı, çok heyecanlıydı.', 5, '2024-12-18 12:00:00'),
(4, 'Emre Roman', 'Roman kitabı harika bir hikayeye sahip, çok sürükleyiciydi.', 4, '2024-12-18 13:00:00'),
(5, 'Fatma Kişisel', 'Kişisel gelişim kitabı, hayatımda değişiklikler yapmama yardımcı oldu.', 5, '2024-12-18 14:00:00'),
(6, 'Ali Edebiyatçı', 'Edebiyat kitabı, derin bir anlam taşıyor ama biraz sıkıcıydı.', 3, '2024-12-18 15:00:00'),
(7, 'Seda Biyograf', 'Biyografi kitabı çok etkileyiciydi, yazarı tanımak çok keyifliydi.', 5, '2024-12-18 16:00:00'),
(8, 'Serkan Sanatçı', 'Sanat kitabı, görsel anlamda çok zengin, oldukça öğreticiydi.', 4, '2024-12-18 17:00:00'),
(9, 'Zeynep Müzik', 'Müzik kitabı çok ilginçti, müzikle ilgili yeni şeyler öğrendim.', 4, '2024-12-18 18:00:00'),
(10, 'Hakan Teknolog', 'Teknoloji kitabı, güncel teknolojilere dair çok önemli bilgiler veriyor.', 5, '2024-12-18 19:00:00'),
(11, 'Emine İş Kadını', 'İş dünyası kitabı, kariyerime yön vermemde bana yardımcı oldu.', 5, '2024-12-18 20:00:00'),
(12, 'Can Çocuk', 'Çocuk kitapları kitabı, çocuklar için çok eğlenceliydi.', 4, '2024-12-18 21:00:00'),
(13, 'Tuncay Sosyolog', 'Sosyoloji kitabı, toplumsal olayları anlamama yardımcı oldu.', 4, '2024-12-18 22:00:00'),
(14, 'İsmail Psikolog', 'Psikoloji kitabı, insan davranışlarını anlamamı sağladı.', 5, '2024-12-18 23:00:00'),
(15, 'Kübra Klasik', 'Klasikler kitabı harika, klasik eserleri tekrar okumak çok keyifliydi.', 5, '2024-12-19 00:00:00'),
(16, 'Mehmet Aşık', 'Aşk kitabı oldukça duygusal ve etkileyiciydi.', 4, '2024-12-19 01:00:00'),
(17, 'Nihan Maceraperest', 'Macera kitabı, oldukça heyecanlıydı, bir solukta bitirdim.', 5, '2024-12-19 02:00:00'),
(18, 'İsmail Hikayeci', 'Hikaye kitabı çok keyifliydi, karakterlerin duygusal derinliği güzeldi.', 4, '2024-12-19 03:00:00'),
(19, 'Deniz Şef', 'Yemek kitapları kitabı, harika tarifler ve mutfak tüyoları içeriyor.', 5, '2024-12-19 04:00:00'),
(20, 'Berk Seyahatçi', 'Seyahat kitabı, gezilecek yerler hakkında çok faydalı bilgiler veriyor.', 5, '2024-12-19 05:00:00'),
(21, 'Feyza Politikacı', 'Politika kitabı, güncel politik durumları çok iyi analiz ediyor.', 4, '2024-12-19 06:00:00');

