<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration {
    public function up(): void
    {
        $this->migrator->add('individualCard.ftp_html_path_polish', '/');
        $this->migrator->add('individualCard.ftp_html_path_deutch', '/');
        $this->migrator->add('individualCard.ftp_html_path_english', '/');
        $this->migrator->add('individualCard.ftp_pdf_path_polish', '/');
        $this->migrator->add('individualCard.ftp_pdf_path_deutch', '/');
        $this->migrator->add('individualCard.ftp_pdf_path_english', '/');

        $this->migrator->add('individualCard.pl_montage', [
            'J' => "natynkowy",
            'K' => "podtynkowy",
            'L' => "sufit modułowy",
            'M' => "g-k",
            'N' => "Nszyna 3 fazowa",
            'O' => "zwieszany",
            'P' => "Słup fi60",
            "Q" => "Słup fi76"
        ]);
        $this->migrator->add('individualCard.de_montage', [
            'J' => "aufputz",                  // natynkowy
            'K' => "unterputz",                // podtynkowy
            'L' => "Modulardecke",             // sufit modułowy
            'M' => "Gipskarton",               // g-k
            'N' => "3-Phasen-Schiene",         // Nszyna 3 fazowa
            'O' => "hängend",                  // zwieszany
            'P' => "Säule Ø60",                // Słup fi60
            "Q" => "Säule Ø76"                 // Słup fi76
        ]);
        
        $this->migrator->add('individualCard.en_montage', [
            'J' => "surface-mounted",          // natynkowy
            'K' => "flush-mounted",            // podtynkowy
            'L' => "modular ceiling",          // sufit modułowy
            'M' => "drywall",                  // g-k
            'N' => "3-phase rail",             // Nszyna 3 fazowa
            'O' => "suspended",                // zwieszany
            'P' => "column Ø60",               // Słup fi60
            "Q" => "column Ø76"                // Słup fi76
        ]);
        

        $this->migrator->add('individualCard.pl_translations', [
            'catalog_card_family' => 'Karta katalogowa rodziny oprawy',
            'catalog_card_led' => 'Karta katalogowa diody',
            'installation_instructions' => 'Instrukcja montażu',
            'product_photos' => 'Zdjęcia produktu',
            'photometric_files' => 'Pliki fotometryczne',
            'compliance_declaration' => 'Deklaracja zgodności',
            'power_supply_catalog_card' => 'Specyfikacja techniczna',
        ]);
        $this->migrator->add('individualCard.de_translations', [
            'catalog_card_family' => 'Katalogkarte der Leuchtenfamilie',
            'catalog_card_led' => 'LED-Katalogkarte',
            'installation_instructions' => 'Montageanleitung',
            'product_photos' => 'Produktfotos',
            'photometric_files' => 'Photometrische Dateien',
            'compliance_declaration' => 'Konformitätserklärung',
            'power_supply_catalog_card' => 'Technische Spezifikation',
        ]);

        $this->migrator->add('individualCard.en_translations', [
            'catalog_card_family' => 'Luminaire family catalog card',
            'catalog_card_led' => 'LED catalog card',
            'installation_instructions' => 'Installation instructions',
            'product_photos' => 'Product photos',
            'photometric_files' => 'Photometric files',
            'compliance_declaration' => 'Declaration of conformity',
            'power_supply_catalog_card' => 'Technical specification',
        ]);


        $this->migrator->add('individualCard.maping', [
            'title' => "E",
            'sku' => "A",
            'main_image' => 'IZ',
            "header" => [
                ["title" => "Ciągi komunikacyjne", "image" => "assets/img/korytarze.png"],
                ["title" => "Doświetlanie budynków, fasad", "image" => "assets/img/fasady.png"],
                ["title" => "Drogi dojazdowe", "image" => "assets/img/droga.png"],
                ["title" => "Drogi gminne", "image" => "assets/img/droga.png"],
                ["title" => "Drogi Osiedlowe", "image" => "assets/img/droga.png"],
                ["title" => "Drogi szybkiego ruchu", "image" => "assets/img/droga.png"],
                ["title" => "Autostrady", "image" => "assets/img/droga.png"],
                ["title" => "Drogi powiatowe", "image" => "assets/img/droga.png"],
                ["title" => "Drogi ekspresowe", "image" => "assets/img/droga.png"],
                ["title" => "Przejścia dla pieszych", "image" => "assets/img/droga.png"],
                ["title" => "Ścieżki rowerowe", "image" => "assets/img/droga.png"],
                ["title" => "Chodniki", "image" => "assets/img/droga.png"],
                ["title" => "Garaże podziemne", "image" => "assets/img/garaze podziemne.png"],
                ["title" => "Hale magazynowe", "image" => "assets/img/halamagazynowa.png"],
                ["title" => "Hale produkcyjne", "image" => "assets/img/halaprzemyslowa.png"],
                ["title" => "Laboratoria, szpitale", "image" => "assets/img/szpital.png"],
                ["title" => "Obiekty handlowe", "image" => "assets/img/handel.png"],
                ["title" => "Obiekty publiczne", "image" => "assets/img/obiektypubliczne.png"],
                ["title" => "Obiekty sportowe", "image" => "assets/img/halasportowa.png"],
                ["title" => "Parki", "image" => "assets/img/placzewnetrzny.png"],
                ["title" => "Parkingi", "image" => "assets/img/placzewnetrzny.png"],
                ["title" => "Place zewnętrzne", "image" => "assets/img/placzewnetrzny.png"],
                ["title" => "Pomieszczenia agewe", "image" => "assets/img/biuro.png"],
                ["title" => "Pomieszczenia czyste", "image" => "assets/img/przemyslczysty.png"],
                ["title" => "Pomieszczenia sanitarne", "image" => "assets/img/szpital.png"],
                ["title" => "Pomieszczenia socjalne", "image" => "assets/img/biuro.png"],
                ["title" => "Pomieszczenia techniczne", "image" => "assets/img/obiektypubliczne.png"],
                ["title" => "Produkcja farmaceutyczna", "image" => "assets/img/szpital.png"],
                ["title" => "Przestrzeń otwarta", "image" => "assets/img/placzewnetrzny.png"],
                ["title" => "Sklepy wielkopowierzchniowe", "image" => "assets/img/handel.png"],
                ["title" => "Tunele", "image" => "assets/img/tunele.png"],
                ["title" => "Wiaty", "image" => "assets/img/placzewnetrzny.png"],
                ["title" => "Myjnie samochodowe", "image" => "assets/img/placzewnetrzny.png"],
                ["title" => "Hodowla drobiu", "image" => "assets/img/szklarnia.png"],
                ["title" => "Obiekty hodowlane", "image" => "assets/img/szklarnia.png"],
                ["title" => "Hodowla trzody chlewnej", "image" => "assets/img/szklarnia.png"],
                ["title" => "Hodowla bydła", "image" => "assets/img/szklarnia.png"],
                ["title" => "Oświetlenie mięsa czerwonego", "image" => "assets/img/handel.png"],
                ["title" => "Oświetlenie pieczywa", "image" => "assets/img/handel.png"],
                ["title" => "Oświetlenie warzyw i owoców", "image" => "assets/img/handel.png"],
                ["title" => "Oświetlenie mięsa białego", "image" => "assets/img/handel.png"],
                ["title" => "Oświetlenie ogrodów wertykalnych", "image" => "assets/img/szklarnia.png"],
                ["title" => "Oświetlenie butików", "image" => "assets/img/handel.png"],
                ["title" => "Produkcja żywności", "image" => "assets/img/halaprzemyslowa.png"]
            ],
            'icons' => [
                [
                    'column' => "AG",
                    'icon' => "assets/img/icons_card_1.svg"
                ],
                [
                    'column' => "AF",
                    'icon' => "assets/img/icons_card_2.svg"
                ],
                [
                    'column' => "AH",
                    'icon' => "assets/img/icons_card_3.svg"
                ],
                [
                    'column' => "CA",
                    'icon' => "assets/img/icons_card_4.svg"
                ],
                [
                    'column' => "CB",
                    'icon' => "assets/img/icons_card_5.svg"
                ],
                [
                    'column' => "AI",
                    'icon' => "assets/img/icons_card_6.svg"
                ],
                [
                    'column' => "BM",
                    'icon' => "assets/img/icons_card_7.svg"
                ],
            ],
            'mounting_system' => [
                'J',
                'K',
                'L',
                'M',
                'N',
                'O',
                'P',
                'Q',
            ],
            'table_general_information' => [
                'warranty' => 'AE',
                'protection_class' => 'AL',
                'luminaire_operating_temperature_1' => 'Z',
                'luminaire_operating_temperature_2' => 'AA',
                'internal_mounting' => 'H',
                'outdoor_mounting' => 'I',
                'montage_system' => '%%montage__value%%',
                'degree_of_protection' => 'X',
                'luminaire_pass_through_system' => 'AN',
                'quick_connect' => 'AO',
                'control_system_power_supply' => 'AD',
            ],
            'tabel_electrical_information' => [
                'rated_power_w' => 'AG',
                'thd_percent' => 'DK',
                'power_supply' => 'DA',
                'max_number_of_luminaires_b10' => 'DD',
                'max_number_of_luminaires_b16' => 'DE',
                'max_number_of_luminaires_c10' => 'DF',
                'max_number_of_luminaires_c16' => 'DG',
                'power_factor_pf_percent' => 'DJ',
                'initial_starting_current_a' => 'DH',
            ],
            'table_psu_information' => [
                'power_supply_lifetime_ta_25_c_h' => 'DM',
                'current_ripple_percent' => 'DC',
                'surge_protection_l_n_l_n_pe_kv' => 'DB',
                'power_supply_version' => 'CZ',
                'additional_power_supply_features' => 'DI',
                'power_supply_output_current_ma' => 'DO',
            ],
            'table_light_source' => [
                'led_brand' => 'CR',
                'led_efficiency_lm_w_ta_25_c' => 'CE',
                'photobiological_safety' => 'CC',
                'l80b10_ta_25_c_h' => 'CJ',
                'l80b10_ta_35_c_h' => 'CK',
                'l80b10_ta_40_c_h' => 'CL',
                'l90b10_ta_25_c_h' => 'CM',
                'l80b50_ta_25_c_h' => 'CI',
                'led_thermal_resistance_k_w' => 'CT',
            ],
            'table_fotometric_information' => [
                'barometric_temperature_cct_k' => 'CA',
                'efficiency_lm_w' => 'AH',
                'luminous_flux_lm' => 'AG',
                'color_rendering_index_ra' => 'CB',
                'standard_deviation_color_matching_sdc' => 'CS',
                'light_distribution_angle' => 'AI',
                'ugr_min_max_1' => 'AJ',
                'ugr_min_max_2' => 'AK',

            ],
            'fotometric_image_url' => 'HY',
            'dimensions_image_url' => 'HZ',
            'dimensions' => [
                'height' => 'BB',
                'length' => 'AZ',
                'width' => 'BA',
            ],
            'gallery' => [
                'IX',
                'IY',
                'JA',
                'JB',
                'JC'
            ]

        ]);

        $this->migrator->add('individualCard.pl_links', [
            'Ogólne warunki gwarancji' => 'https://luxon.pl/ogolne-warunki-GWARANCJI/',
            'Ogólne warunki sprzedaży' => 'https://luxon.pl/ogolne-warunki-sprzedazy/',
            'Formularz reklamacyjny' => 'https://luxon.pl/zgloszenie-reklamacyjne/',
        ]);
        $this->migrator->add('individualCard.pl_maping', [
            'short_description' => "B",
            'application' => [
                "DT",
                "DU",
                "DV",
                "DW",
                "DX",
                "DY"
            ],
            'table_materials' => [
                'housing_material' => 'R',
                'housing_color' => 'S',
                'housing_ral_color' => 'T',
                'diffuser_material' => 'V',
                'lens_material' => 'U',
                'diffuser_glass_thickness_mm' => 'W',
                'impact_resistance_ik' => 'Y',
            ],
            'files' => [
                'catalog_card_family' => 'FE',
                'catalog_card_led' => 'IA',
                'installation_instructions' => 'IE',
                'technical_specification' => 'IN',
                'product_photos' => 'JD',
                'photometric_files' => 'IK',
                'compliance_declaration' => 'FK',
                'enec_certificate' => 'FR',
                'enec_plus_certificate' => 'FS',
                'pzh_certificate' => 'FT',
                'haccp_certificate' => 'FU',
                'rohs_certificate' => 'FV',
                'reach_certificate' => 'FW',
                'cnbop_certificate' => 'FX',
                'emc_certificate' => 'FY',
                'power_supply_catalog_card' => 'IT',
            ],
        ]);

        $this->migrator->add('individualCard.pl_description', 'Pobór mocy [AG] W. Skuteczność świetlna oprawy [AH] lm/W.  Strumień świetlny na poziomie [AF] Im. Rozsył światła [AI]. Oprawa do zastosowania w: [applications]

Oprawa posiada szczelność IP [X]. Odporność mechaniczna IK [Y]. Dopuszczalna temperatura otoczenia TA min [Z]°C - TA max [AA]°C. Układ optyczny wykonany z [U]. W oprawie zastosowano diody [CR] o tolerancji temperatury barwowej (wg elips MacAdama) SDCM [CS]. Temperatura barwowa [CA] K.

Średni okres trwałości znamionowej L80B10 na poziomie [CJ] godzin dla Ta =  25°C. Średni okres trwałości znamionowej L80B10 na poziomie [CK] godzin dla Ta =  35⁰C. Średni okres trwałości znamionowej L80B50 na poziomie [CI] godzin dla Ta =25°C. Średni okres trwałości znamionowej L90B10 na poziomie  [CM] godzin dla Ta =25°C. Produkt zaliczany jest do grupy ryzyka fotobiologicznego [CC]. Wskaźnik oddawania barw (CRI)   [CB] Ra. 

Wykorzystany w oprawie zasilacz pozwala na pracę w temperaturach od  [Z]°C do  [AA]°C. Czas pracy zasilacza wynosi [DM] godzin. Power Factor (PF)  [DJ]. Współczynnik zniekształceń harmonicznych (THD)  [CK]%. Oprawa działa przy zasilaniu [DA].Tętnienie prądu wyjściowego [DC]. Żywotność oprawy wynosi [AC] h przy temperaturze pracy równej 25°C. Możliwość sterowania: [AD]. 

Wymiary:  {{[BB]mm wysokość}}, {{[AZ]mm długość}}, {{[BA]mm szerokość}}. {{Materiał obudowy [R]}}, {{w kolorze [S]}} {{o kodzie [T]}}. Waga oprawy wynosi [BM]kg. Maksymalna ilość opraw w jednym obwodzie dla: {{B10 = [DD]}} {{B16 = [DE]}} {{C10 = [DF]}} {{C16 = [DG]}}');

        $this->migrator->add('individualCard.pl_fixed_translations', [
            'montage_system' => "System Montażu",
            "footer_copyright" => "© 2023, Luxon Sp. z o.o. Wszystkie prawa zastrzeżone.",
            "footer_release" => "rok wydania 2023",
            "card" => "KARTA KATALOGOWA PRODUKTU",
            "warranty" => "Gwarancja [R]",
            "general_information" => "Ogólne informacje",
            "protection_class" => "Klasa ochronności I",
            "luminaire_operating_temperature" => "Temperatura pracy oprawy [°C]",
            "internal_mounting" => "Montaż wewnętrzny",
            "outdoor_mounting" => "Montaż zewnętrzny",
            "montage" => "System montażu",
            "degree_of_protection" => "Stopień ochrony",
            "luminaire_pass_through_system" => "System przelotowy oprawy",
            "quick_connect" => "Szybkozłącze",
            "control_system_power_supply" => "System sterowania",
            "electrical_information" => "Dane elektryczne",
            "rated_power_w" => "Moc znamionowa [W]",
            "thd_percent" => "THD [%] 10",
            "power_supply" => "Zasilanie",
            "max_number_of_luminaires_b10" => "Maks. ilość opraw w jednym obwodzie B10",
            "max_number_of_luminaires_b16" => "Maks. ilość opraw w jednym obwodzie B16",
            "max_number_of_luminaires_c10" => "Maks. ilość opraw w jednym obwodzie C10",
            "max_number_of_luminaires_c16" => "Maks. ilość opraw w jednym obwodzie C16",
            "power_factor_pf_percent" => "współczynnik mocy PF [%]",
            "initial_starting_current_a" => "Początkowy prąd rozruchowy [A]",
            "psu_information" => "Specyfikacja systemu zasilania",
            "power_supply_lifetime_ta_25_c_h" => "Żywotność zasilacza dla Ta=25°C [h]",
            "current_ripple_percent" => "Tętnienie prądu [%]",
            "surge_protection_l_n_l_n_pe_kv" => "Ochrona przed przepięciem (L-N)/(L/N-PE) [kV]",
            "power_supply_version" => "Wersja zasilacza",
            "additional_power_supply_features" => "Dodatkowe funkcje zasilacza",
            "power_supply_output_current_ma" => "Prąd wyjściowy zasilacza [mA]",
            "light_source_data" => "Dane źródła światła",
            "led_brand" => "Marka zastosowanej diody",
            "led_efficiency_lm_w_ta_25_c" => "Efektywność diody [lm/W] Ta=25°C",
            "photobiological_safety" => "Bezpieczeństwo fotobiologiczne",
            "l80b10_ta_25_c_h" => "L80B10; Ta=25°C [h]",
            "l80b10_ta_35_c_h" => "L80B10; Ta=35°C [h]",
            "l80b10_ta_40_c_h" => "L80B10; Ta=40°C [h]",
            "l90b10_ta_25_c_h" => "L90B10; Ta=25°C [h]",
            "l80b50_ta_25_c_h" => "L80B50; Ta=25°C [h]",
            "led_thermal_resistance_k_w" => "Rezystancja termiczna diody [K/W]",
            "materials" => "Materiał obudowy",
            "housing_material" => "Materiał obudowy",
            "housing_color" => "Kolor obudowy",
            "housing_ral_color" => "Kolor RAL obudowy ",
            "diffuser_material" => "Materiał dyfuzora",
            "lens_material" => "Materiał soczewki",
            "diffuser_glass_thickness_mm" => "Grubość szkła dyfuzora [mm]",
            "impact_resistance_ik" => "Odporność na uderzenia (IK)",
            "photometric_data" => "Dane fotometryczne oprawy",
            "barometric_temperature_cct_k" => "Temperatura barowa CCT [K]",
            "efficiency_lm_w" => "Efektywność [lm/W]",
            "luminous_flux_lm" => "Strumień świetlny oprawy [lm]",
            "color_rendering_index_ra" => "Wskaźnik oddawania barw (Ra)",
            "standard_deviation_color_matching_sdc" => "Standardowe odchylenie dopasowania barw (SDCM)",
            "light_distribution_angle" => "Kąt rozsyłu światła",
            "ugr_min_max" => "UGR min. - max",
            "dimensions" => "Wymiary",
            "height" => "Wysokość (H) [mm]",
            "length" => "Długość (L) [mm]",
            "width" => "Szerokość (W) [mm]",
            "catalog_card_family" => "Karta katalogowa rodziny oprawy",
            "catalog_card_led" => "Karta katalogowa diody",
            "installation_instructions" => "Instrukcja montażu",
            "technical_specification" => "Specyfikacja techniczna",
            "compliance_declaration" => "Deklaracja zgodności CE",
            "product_photos" => "Zdjęcia produktu",
            "photometric_files" => "Pliki fotometryczne",
            "links" => "Linki",
            "general_warranty_terms_and_conditions" => "Ogólne warunki gwarancji",
            "general_terms_and_conditions_of_sale" => "Ogólne warunki sprzedaży",
            "complaint_form" => "Formularz reklamacyjny",
            "general" => "Dane ogólne",
            "gallery" => "Galeria zdjęć",
            "application_title" => "Zastosowania",
            "enec_certificate" => "Certyfikat ENEC",
            "enec_plus_certificate" => "Certyfikat ENEC +",
            "pzh_certificate" => "PZH",
            "haccp_certificate" => "HACCP",
            "rohs_certificate" => "ROHS",
            "reach_certificate" => "REACH",
            "cnbop_certificate" => "CNBOP",
            "emc_certificate" => "EMC",
            "power_supply_catalog_card" => "Karta katalogowa zasilacza",
            "yes" => "TAK",
            "no" => "NIE",
            'iconsBar_title' => "Podstawowe Informacje",
            'psu_table_title' => 'Dane techniczne układu zasilającego',
            'materials_table_title' => "Materiały",
            'download' => "Pobierz",
            'see' => "Zobacz",
            'files_title' => "Pliki i odnośniki",
            'files_heading' => 'Nazwa Pliku'
        ]);






        //English
        $this->migrator->add('individualCard.en_description', 'Power consumption [AG] W. Luminous efficacy [AH] lm/W. Luminous flux of [AF] lm. Spread of light [AI]. Luminaire intended for use in: [applications]

 Luminaire IP rating [X]. Mechanical resistance IK [Y]. Acceptable ambient temperature Ta min [Z]°C - Ta max [AA]°C. Optical system made of [FC]. The luminaire uses [CR] LEDs with colour temperature tolerance (acc. to MacAdam ellipses) SDCM [CS]. Colour temperature [CA] K.

Average rated life of L80B10 at [CJ] hours at Ta = 25°C. Average rated life of L80B10 at [CK] hours at Ta = 35⁰C. Average rated life of L80B50 at [CI] hours at Ta = 25°C. Average rated life of L90B10 at [CM] hours at Ta = 25°C. The product is classified for photobiological risk [CC]. Colour rendering index (CRI) > [CB] Ra.

The operating time of the power supply is [DM] hours at Ta = 25°C. Power Factor (PF) [DJ]. Total harmonic distortions (THD) [DK]%. The luminaire operates on a [DA] power supply. Output current ripple [DC]%. The lifetime of the luminaire is [AC] h at 25°C. It can be controlled by [AD].

Dimensions: {{[BB] mm height}} {{[AZ] mm length}} {{[BA] mm width}}. {{Housing material [EZ]}}, {{in [EQ] color}} - {{RAL [T]}}. The weight of the luminaire is [BM] kg. Max. number of luminaires in one circuit for: {{B10 = [DD]}} {{B16 = [DE]}} {{C10 = [DF]}} {{C16 = [DG]}}.');
        $this->migrator->add('individualCard.en_fixed_translations', [
            'montage_system' => "Montage System",
            "footer_copyright" => "© 2023, Luxon Sp. z o.o. All rights reserved",
            "footer_release" => "Edition year 2023",
            "card" => "Product Data Sheet",
            "warranty" => "Warranty [Y]",
            "general_information" => "General information",
            "protection_class" => "Protection rating",
            "luminaire_operating_temperature" => "Luminaire operating temperature",
            "internal_mounting" => "Internal installation",
            "outdoor_mounting" => "External installation",
            "montage" => "Mounting system",
            "degree_of_protection" => "IP protection class",
            "luminaire_pass_through_system" => "Through wiring system",
            "quick_connect" => "Quick connector",
            "control_system_power_supply" => "Control system",
            "electrical_information" => "Electrical data",
            "rated_power_w" => "Rated power [W]",
            "thd_percent" => "THD [%]",
            "power_supply" => "Power",
            "max_number_of_luminaires_b10" => "Max. number of luminaires in a single B10 circuit",
            "max_number_of_luminaires_b16" => "Max. number of luminaires in a single B16 circuit",
            "max_number_of_luminaires_c10" => "Max. number of luminaires in a single C10 circuit",
            "max_number_of_luminaires_c16" => "Max. number of luminaires in a single C16 circuit",
            "power_factor_pf_percent" => "Power Factor [%]",
            "initial_starting_current_a" => "Inrush current [A]",
            "psu_information" => "Supply system specifications",
            "power_supply_lifetime_ta_25_c_h" => "Power supply lifetime for Ta=25°C [h]",
            "current_ripple_percent" => "Current ripple [%]",
            "surge_protection_l_n_l_n_pe_kv" => "Overvoltage protection (L-N)/(L/N-PE) [kV]",
            "power_supply_version" => "Power supply version ON/OFF",
            "additional_power_supply_features" => "Additional power supply features NFC",
            "power_supply_output_current_ma" => "Power supply current output [mA]",
            "light_source_data" => "Light source data",
            "led_brand" => "LED brand Samsung",
            "led_efficiency_lm_w_ta_25_c" => "LED efficiency [lm/W] Ta=25°C",
            "photobiological_safety" => "Photobiological safety",
            "l80b10_ta_25_c_h" => "L80B10; Ta=25°C [h]",
            "l80b10_ta_35_c_h" => "L80B10; Ta=35°C [h]",
            "l80b10_ta_40_c_h" => "L80B10; Ta=40°C [h]",
            "l90b10_ta_25_c_h" => "L90B10; Ta=25°C [h]",
            "l80b50_ta_25_c_h" => "L80B50; Ta=25°C [h]",
            "led_thermal_resistance_k_w" => "LED thermal resistance [K/W]",
            "materials" => "Materials",
            "housing_material" => "Housing material",
            "housing_color" => "Housing color white",
            "housing_ral_color" => "Housing RAL 9016",
            "diffuser_material" => "Diffuser material Matte PC",
            "lens_material" => "Lens material",
            "diffuser_glass_thickness_mm" => "Diffuser thickness [mm] n/a",
            "impact_resistance_ik" => "Impact resistance (IK) n/a",
            "photometric_data" => "Luminaire photometric data",
            "barometric_temperature_cct_k" => "Color temperature CCT [K]",
            "efficiency_lm_w" => "Efficacy [lm/W]",
            "luminous_flux_lm" => "Luminous flux [lm]",
            "color_rendering_index_ra" => "Color rendering index (Ra)",
            "standard_deviation_color_matching_sdc" => "Standard deviation of color matching",
            "light_distribution_angle" => "Light beam angle",
            "ugr_min_max" => "UGR min. - max",
            "dimensions" => "Dimensions",
            "height" => "Height [H]",
            "length" => "Length [L]",
            "width" => "Width [W]",
            "catalog_card_family" => "Datasheet of the luminaire family",
            "catalog_card_led" => "LED datasheet",
            "installation_instructions" => "Installation instructions",
            "technical_specification" => "Technical specification",
            "compliance_declaration" => "Declaration of conformity CE",
            "product_photos" => "Product photos",
            "photometric_files" => "Photometric files",
            "links" => "References",
            "general_warranty_terms_and_conditions" => "General warranty terms",
            "general_terms_and_conditions_of_sale" => "General terms of sale",
            "complaint_form" => "Complaint form",
            "general" => "General data",
            "gallery" => "Photo Gallery",
            "application_title" => "Applications",
            "enec_certificate" => "ENEC certificate",
            "enec_plus_certificate" => "ENEC Certificate +",
            "pzh_certificate" => "PZH",
            "haccp_certificate" => "HACCP",
            "rohs_certificate" => "ROHS",
            "reach_certificate" => "REACH",
            "cnbop_certificate" => "CNBOP",
            "emc_certificate" => "EMC",
            "power_supply_catalog_card" => "Datasheet of the power supply",
            "yes" => "YES",
            "no" => "NO",
            'iconsBar_title' => "Basic Information",
            'psu_table_title' => 'Power Supply System Data',
            'materials_table_title' => "Materials",
            'download' => "Download",
            'see' => "View",
            'files_title' => "Files and Links",
            'files_heading' => 'File Name',

        ]);


        $this->migrator->add('individualCard.en_links', [
            'Allgemeine Garantiebestimmungen' => 'https://luxonled.de/garantiebedingungen/',
            'AGB' => 'https://luxonled.de/allgemeine-geschaftsbedingungen/',
            'Reklamationsformular' => 'https://luxonled.de/beschwerdeformular/'
        ]);
        $this->migrator->add('individualCard.en_maping', [
            'short_description' => "C",
            'application' => [
                "HA",
                "HB",
                "HC",
                "HD",
                "HE",
                "HF"
            ],
            'table_materials' => [
                'housing_material' => 'EZ',
                'housing_color' => 'EQ',
                'housing_ral_color' => 'T',
                'diffuser_material' => 'Ew',
                'lens_material' => 'Fc',
                'diffuser_glass_thickness_mm' => 'W',
                'impact_resistance_ik' => 'Y',
            ],
            'files' => [
                'catalog_card_family' => 'FF',
                'catalog_card_led' => 'IA',
                'installation_instructions' => 'IF',
                'technical_specification' => 'IO',
                'product_photos' => 'JD',
                'photometric_files' => 'IK',
                'compliance_declaration' => 'FL',
                'enec_certificate' => 'FR',
                'enec_plus_certificate' => 'FS',
                'pzh_certificate' => 'FT',
                'haccp_certificate' => 'FU',
                'rohs_certificate' => 'FV',
                'reach_certificate' => 'FW',
                'cnbop_certificate' => 'FX',
                'emc_certificate' => 'FY',
                'power_supply_catalog_card' => 'IT',
            ],
        ]);





        //Deutch
        $this->migrator->add('individualCard.de_description', 'Leistungsaufnahme [AG] W. Lichtausbeute [AH] lm/W. Lichtstrom von [AF] lm. Lichtverteilung [AI].Leuchte zur Verwendung in: [applications]

        Die Leuchte verfügt über die Schutzart [X]. Mechanischer Widerstand IK [Y]. Zulässige Umgebungstemperatur Ta min [Z]°C - TA max [AA]°C. Optisches System hergestellt aus [FD]. Die Leuchte verwendet Dioden [CR] mit einer Farbtemperaturtoleranz (gemäß MacAdam-Ellipsen) SDCM [CS]. Farbtemperatur [CA] K.
       
       Die durchschnittliche Nennlebensdauer von L80B10 beträgt [CJ] Stunden bei Ta = 25 °C. Die durchschnittliche Nennlebensdauer von L80B10 beträgt [CK] Stunden bei Ta = 35 °C. Die durchschnittliche Nennlebensdauer von L80B50 beträgt [CI] Stunden bei Ta = 25 °C. Die durchschnittliche Nennlebensdauer von L90B10 beträgt [CM] Stunden bei Ta = 25 °C. Das Produkt wird als photobiologisches Risiko eingestuft [CC]. Farbwiedergabe-Index (CRI) > [CB] Ra.
       
       Die Betriebszeit des Netzteils beträgt [DM] Stunden. Power Factor (Leistungsfaktor) (PF) [DJ]. Gesamte harmonische Verzerrung (THD) [DK]%. Die Leuchte wird mit einer Spannung von [DA] betrieben. Welligkeit des Ausgangsstroms [DC]%. Die Lebensdauer der Leuchte beträgt [AC] h bei 25 °C. Kann durch [AD] gesteuert werden.
       
       Abmessungen: {{Höhe [BB] mm}} {{Länge [AZ] mm}} {{Breite [BA] mm}}. {{Gehäuseausführung [FA]}} {{in der Farbe [ER] -}} {{RAL [T]}}. Das Gewicht der Leuchte beträgt [BM] kg. Maximale Anzahl von Leuchten je Stromkreis für: {{B10 = [DD]}} {{B16 = [DE]}} {{C10 = [DF]}} {{C16 = [DG]}}.');
        $this->migrator->add(
            'individualCard.de_fixed_translations',
            [
                'montage_system' => "Befestigungssystem",
                "footer_copyright" => "© 2023, Luxon LED GmbH. Alle Rechte vorbehalten",
                "footer_release" => "Ausgabejahr 2023",
                "card" => "Produktdatenblatt",
                "warranty" => "Garantie [J]",
                "general_information" => "Allgemeine Angaben",
                "protection_class" => "Schutzklasse",
                "luminaire_operating_temperature" => "Betriebstemperatur der Leuchte",
                "internal_mounting" => "Innenbereich",
                "outdoor_mounting" => "Außenbereich",
                "montage" => "Montagesystem",
                "degree_of_protection" => "Schutzart",
                "luminaire_pass_through_system" => "Leuchtendurgangssystem",
                "quick_connect" => "Schnellkupplungsstecker",
                "control_system_power_supply" => "Steuersystem",
                "electrical_information" => "Elektrische Daten",
                "rated_power_w" => "Nennleistung [W]",
                "thd_percent" => "THD [%]",
                "power_supply" => "Stromversorgung",
                "max_number_of_luminaires_b10" => "Max. Anzahl der Leuchten pro Stromkreis B10",
                "max_number_of_luminaires_b16" => "Max. Anzahl der Leuchten pro Stromkreis B16",
                "max_number_of_luminaires_c10" => "Max. Anzahl der Leuchten pro Stromkreis C10",
                "max_number_of_luminaires_c16" => "Max. Anzahl der Leuchten pro Stromkreis C16",
                "power_factor_pf_percent" => "Power Factor PF [%]",
                "initial_starting_current_a" => "Anlaufstrom [A]",
                "psu_information" => "Technische Daten des Versorgungssystems",
                "power_supply_lifetime_ta_25_c_h" => "Lebensdauer des Stromversorgungssystems [Std.]",
                "current_ripple_percent" => "Welligkeitsstrom [%]",
                "surge_protection_l_n_l_n_pe_kv" => "Überspannungsschutz (L-N)/(L/N-PE) [kV]",
                "power_supply_version" => "Netzteil-Version ON/OFF",
                "additional_power_supply_features" => "Zusätzliche Netzteilfunktionen NFC",
                "power_supply_output_current_ma" => "Ausgangsstrom des Netzteils [mA]",
                "light_source_data" => "Daten der Lichtquelle",
                "led_brand" => "Hersteller der LED-Lichtquelle",
                "led_efficiency_lm_w_ta_25_c" => "Wirkungsgrad der Lichtquelle [lm/W] Ta=25°C",
                "photobiological_safety" => "Photobiologische Sicherheit",
                "l80b10_ta_25_c_h" => "L80B10; Ta=25°C [h]",
                "l80b10_ta_35_c_h" => "L80B10; Ta=35°C [h]",
                "l80b10_ta_40_c_h" => "L80B10; Ta=40°C [h]",
                "l90b10_ta_25_c_h" => "L90B10; Ta=25°C [h]",
                "l80b50_ta_25_c_h" => "L80B50; Ta=25°C [h]",
                "led_thermal_resistance_k_w" => "Wärmewiderstand der LED-Lichtquelle [K/W]",
                "materials" => "Material",
                "housing_material" => "Gehäusematerial",
                "housing_color" => "Gehäusefarbe weiß",
                "housing_ral_color" => "RAL-Gehäusefarbe 9016",
                "diffuser_material" => "Diffusormaterial mattes PC",
                "lens_material" => "Linsenmaterial",
                "diffuser_glass_thickness_mm" => "Dicke des Diffusors [mm]",
                "impact_resistance_ik" => "Schlagfestigkeit (IK)",
                "photometric_data" => "Photometrische Daten der Leuchte",
                "barometric_temperature_cct_k" => "Farbtemperatur CCT [K]",
                "efficiency_lm_w" => "Lichtausbeute [lm/W]",
                "luminous_flux_lm" => "Lichtstrom der Leuchte [lm]",
                "color_rendering_index_ra" => "Farbwiedergabeindex (Ra)",
                "standard_deviation_color_matching_sdc" => "Standardabweichung des Farbabgleichs (SDCM)",
                "light_distribution_angle" => "Abstrahlwinkel",
                "ugr_min_max" => "UGR min. - max",
                "dimensions" => "Abmessungen der Leuchte",
                "height" => "Höhe (H) [mm]",
                "length" => "Länge (L) [mm]",
                "width" => "Breite (W) [mm]",
                "catalog_card_family" => "Leuchtenfamilie Datenblatt",
                "catalog_card_led" => "LED-Diode Datenblatt",
                "installation_instructions" => "Montageanleitung",
                "technical_specification" => "Technische Spezifikation",
                "compliance_declaration" => "EG-Konformitätserklärung",
                "product_photos" => "Produktfotos",
                "photometric_files" => "Photometrische Dateien",
                "links" => "Links",
                "general_warranty_terms_and_conditions" => "Allgemeine Garantiebestimmungen",
                "general_terms_and_conditions_of_sale" => "AGB",
                "complaint_form" => "Reklamationsformular",
                "general" => "Allgemeine Angaben",
                "gallery" => "Fotogalerie",
                "application_title" => "Anwendungen",
                "enec_certificate" => "ENEC-Zertifikat",
                "enec_plus_certificate" => "ENEC-Zertifikat +",
                "pzh_certificate" => "PZH",
                "haccp_certificate" => "HACCP",
                "rohs_certificate" => "ROHS",
                "reach_certificate" => "REACH",
                "cnbop_certificate" => "CNBOP",
                "emc_certificate" => "EMC",
                "power_supply_catalog_card" => "Datenblatt des Netzteils",
                "yes" => "JA",
                "no" => "NEIN",
                'iconsBar_title' => "Grundlegende Informationen",
                'psu_table_title' => 'Technische Daten des Versorgungssystems',
                'materials_table_title' => "Materialien",
                'download' => "Herunterladen",
                'see' => "Ansehen",
                'files_title' => "Dateien und Links",
                'files_heading' => 'Dateiname',
            ]
        );


        $this->migrator->add('individualCard.de_links', [
            'Allgemeine Garantiebestimmungen' => 'https://luxonled.de/garantiebedingungen/',
            'AGB' => 'https://luxonled.de/allgemeine-geschaftsbedingungen/',
            'Reklamationsformular' => 'https://luxonled.de/beschwerdeformular/'
        ]);
        $this->migrator->add('individualCard.de_maping', [
            'short_description' => "C",
            'application' => [
                "HG",
                "HH",
                "HI",
                "HJ",
                "HK",
                "HL"
            ],
            'table_materials' => [
                'housing_material' => 'FA',
                'housing_color' => 'ER',
                'housing_ral_color' => 'T',
                'diffuser_material' => 'EX',
                'lens_material' => 'FD',
                'diffuser_glass_thickness_mm' => 'W',
                'impact_resistance_ik' => 'Y',
            ],
            'files' => [
                'catalog_card_family' => 'FG',
                'catalog_card_led' => 'IA',
                'installation_instructions' => 'IG',
                'technical_specification' => 'IP',
                'product_photos' => 'JD',
                'photometric_files' => 'IK',
                'compliance_declaration' => 'FM',
                'enec_certificate' => 'FR',
                'enec_plus_certificate' => 'FS',
                'pzh_certificate' => 'FT',
                'haccp_certificate' => 'FU',
                'rohs_certificate' => 'FV',
                'reach_certificate' => 'FW',
                'cnbop_certificate' => 'FX',
                'emc_certificate' => 'FY',
                'power_supply_catalog_card' => 'IT',
            ],
        ]);


    }
};


