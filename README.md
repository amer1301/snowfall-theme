# Snowfall Theme

Snowfall Theme är ett egenutvecklat klassiskt WordPress-tema skapat som en del av projektuppgift i webbutveckling med WordPress CMS. Temat är framtaget för en fiktiv företagswebbplats – *Snowfall Adventures* – som erbjuder aktiviteter, boende och matupplevelser i fjällen.

Temat är byggt med fokus på:
- tydlig struktur
- tillgänglighet
- redigerbarhet för icke-tekniska användare
- korrekt WordPress-implementation

---

## 📌 Funktioner

- Klassiskt WordPress-tema (PHP-mallar)
- Dynamiskt innehåll via WordPress CMS
- Blockredigeraren (Gutenberg) för sidinnehåll
- Egna inställningar via WordPress Customizer
- Responsiv design (desktop, tablet, mobil)
- Sökmotoroptimerad struktur
- Tillgänglighetsanpassningar enligt WCAG-principer
- Anpassningar för *The Events Calendar*
- Embed-/iframe-läge för boknings- och eventvisning

---

## 🧱 Temastruktur

```text
snowfall-theme/
├─ assets/
│  ├─ images/
│  ├─ js/
│  └─ css/
├─ footer.php
├─ front-page.php
├─ functions.php
├─ header.php
├─ index.php
├─ page-about.php
├─ page-activities.php
├─ page-booking.php
├─ page-contact.php
├─ page-news.php
├─ style.css
└─ README.md

##🧭 Navigering

Navigering hanteras via WordPress inbyggda menysystem och wp_nav_menu().
Administratörer kan skapa och redigera menyer via Utseende → Menyer utan att ändra kod.

##🎛️ Anpassningsläge (Customizer)

Temat innehåller flera egna sektioner i Utseende → Anpassa, bland annat:
- Webbplatsens identitet
- Hero – startsida
- Hero – bokningssida
- Bokningsbar (texter och inställningar)
- Puffar på startsidan
- Citatsektion
- Bildsektioner
- Inställningar för startsidan
Dessa inställningar gör det möjligt att redigera globala och återkommande delar av webbplatsen utan teknisk kunskap.

##👥 Nyckelpersoner / Personal
Temat innehåller en Custom Post Type (CPT) för nyckelpersoner/team.
Funktioner:
- Skapa och redigera nyckelpersoner via wp-admin
- Stöd för titel, innehåll och bild
- Visning via shortcode i valfri sida

##📰 Nyheter
Nyhetsfunktionalitet bygger på WordPress inlägg:
- Kategoriserade nyheter
- Stöd för featured image
- Egen sidmall för nyhetssida

##📅 Events & bokning
Temat är anpassat för pluginet The Events Calendar:
- Anpassningar för validering och layout
- Förenklad vy vid embed/iframe-läge
- Möjlighet att bädda in events via ?snowfall_embed=1

##🖼️ Embed / iframe-läge
Temat stöder ett särskilt embed-läge:
- Aktiveras via URL-parametern ?snowfall_embed=1
- Lägger till is-embed som body-class
- Döljer admin-bar
- Anpassar layout för inbäddade vyer
- Fallback-detektion via JavaScript när sidan visas i iframe

##♿ Tillgänglighet
Temat har utvecklats med fokus på tillgänglighet:
- Semantisk HTML
- Tydlig rubrikhierarki
- Synliga fokusmarkeringar
- Alt-texter för bilder
- Testat med WAVE och HTML-validator
Vissa WordPress-core-element har justerats för att undvika valideringsfel, vilket är dokumenterat i koden.

##🔍 SEO
SEO hanteras genom:
- Strukturerad HTML
- Korrekt rubrikanvändning
- Optimerade bilder
- SEO-plugin (t.ex. Yoast SEO)
- Intern länkning mellan sidor
SEO-strategi och genomförande beskrivs i projektrapporten.

##🛠️ Installation
1. Ladda upp mappen snowfall-theme till /wp-content/themes/
2. Aktivera temat via Utseende → Teman
3. Skapa menyer via Utseende → Menyer
4. Anpassa innehåll via Utseende → Anpassa
5. Lägg till sidor, inlägg och nyckelpersoner via wp-admin
