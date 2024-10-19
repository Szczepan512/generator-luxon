<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration {
    public function up(): void
    {

        //Maping 
        $this->migrator->add('familyCard.maping', [

            "sku" => "A",
            "title" => "E",
            "thumbnail" => "IZ",
            "installation_image" => "IZ",
            "header" => [
                "DT",
                "DU",
                "DV",
                "DW",
                "DX",
                "DY"
            ],
            "installation" => [
                [
                    'slug' => 'surface_mounting',
                    'column' => 'J',
                    "icon" => "assets/img/surface_mounting_icon.png",
                    "blueprint" => "assets/img/surface_mounting_blueprint.png"
                ],
                [
                    'slug' => 'pre-wall_installation',
                    'column' => 'K',
                    "icon" => "assets/img/pre-wall_installation_icon.png",
                    "blueprint" => "assets/img/pre-wall_installation_blueprint.png"
                ],
                [
                    'slug' => 'modular_ceiling_assembly',
                    'column' => 'L',
                    "icon" => "assets/img/modular_ceiling_assembly_icon.png",
                    "blueprint" => "assets/img/modular_ceiling_assembly_blueprint.png"
                ],
                [
                    'slug' => 'special_ceiling_assembly',
                    'column' => 'M',
                    "icon" => "assets/img/special_ceiling_assembly_icon.png",
                    "blueprint" => "assets/img/special_ceiling_assembly_blueprint.png"
                ],
                [
                    'slug' => 'pipe_installation_fi76',
                    'column' => 'Q',
                    "icon" => "assets/img/pipe_installation_fi76_icon.png",
                    "blueprint" => "assets/img/pipe_installation_fi76_blueprint.png"
                ],
                [
                    'slug' => 'pipe_installation_fi60',
                    'column' => 'P',
                    "icon" => "assets/img/pipe_installation_fi60_icon.png",
                    "blueprint" => "assets/img/pipe_installation_fi60_blueprint.png"
                ],
                [
                    'slug' => 'pendant_mounting',
                    'column' => 'O',
                    "icon" => "assets/img/pendant_mounting_icon.png",
                    "blueprint" => "assets/img/pendant_mounting_blueprint.png"
                ],
                [
                    'slug' => '3-phase_rail_mounting',
                    'column' => 'N',
                    "icon" => "assets/img/3-phase_rail_mounting_icon.png",
                    "blueprint" => "assets/img/3-phase_rail_mounting_blueprint.png"
                ],
            ],
            "icons_text" => [
                [
                    'slug' => 'voltage',
                    'column' => 'DA'
                ],
                [
                    'slug' => 'ip',
                    'column' => 'X'
                ],
                [
                    'slug' => 'ik',
                    'column' => 'Y'
                ],
            ],
            "icons" => [
                [
                    'slug' => "protection",
                    'column' => "AL",
                ],
                [
                    'slug' => "ce",
                    'column' => "AR",
                ],
                [
                    'slug' => "outdoor",
                    'column' => "I",
                ],
                [
                    'slug' => "indoor",
                    'column' => "H",
                ],
                [
                    'slug' => "psu",
                    'column' => "CZ",
                ],
            ],
            "dimensions_image" => "HZ",
            "dimensions" => [
                "l" => "BA",
                "w" => "AZ",
                "h" => "BB"
            ],
            "package" => [
                "package" => "JZ",
                "palette" => "KA",
                "palette-sizes" => "KB"
            ],
        ]);


        //Polish Language
        $this->migrator->add('familyCard.pl_maping', [
            "DZ",
            "EA",
            "EB",
            "EC",
            "ED",
            "EE"
        ]);

        $this->migrator->add('familyCard.pl_translations', [
            "subtitle" => "Zalety",
            "lightspread" => "Natężenie światła",
            "configuration" => "Dostępne konfiguracje",
            "example" => "Przykładowy kod produktu",
            "under_tables" => "*W zależności od zastosowanej soczewki, moc strumienia świetlnego może się różnić, dlatego w tabeli mogą być podane zakresy strumienia świetlnego dla danej wersji mocy, służące celom poglądowym.",
            "footer" => "Aby poznać dokładne parametry światła dla wybranego kąta wiązki, należy skorzystać z plików fotometrycznych dla określonego kąta wiązki. Link do pobrania:<a href=\"luxon.pl/Pliki/https://luxon.pl/Pliki/LDT_LUXON_LED.zip\">luxon.pl/Pliki/LDT_LUXON_LED.zip</a>",
            "name" => "Montaż",
        ]);

        $this->migrator->add('familyCard.pl_installations', [
            "surface_mounting" => "Natynkowy",
            "pre-wall_installation" => "Podtynkowy",
            "modular_ceiling_assembly" => "Sufit modułowy",
            "special_ceiling_assembly" => "Sufit G-K",
            "3-phase_rail_mounting" => "Szyna 3-fazowa",
            "pendant_mounting" => "Zwieszany",
            "pipe_installation_fi60" => "Słup Ø60",
            "pipe_installation_fi76" => "Słup Ø76"
        ]);

        $this->migrator->add('familyCard.pl_columns', [
            1 => "Rodzina",
            2 => "Wersja mocowa",
            3 => "Wersja ",
            4 => "Moc [W]",
            5 => "CRI",
            6 => "Typ diody",
            7 => "CCT [K]",
            8 => "Materiał soczewki",
            9 => "Materiał dyfuzora",
            10 => "Kąt rozsyłu",
            11 => "Sterowanie",
            12 => "Temperatura pracy",
            13 => "Gwarancja",
            14 => "Modyfikacje"
        ]);

        $this->migrator->add('familyCard.pl_configurator', [
            "3" => [
                "0" => "Standard",
                "1" => "LIft 1",
                "2" => "LIft 2",
                "8" => "ENCE",
                "9" => "ENEC +",
            ],
            "6" => [
                "A" => "Mid Power",
                "B" => "Highpower",
                "C" => "Highpower 1",
                "D" => "COB",
                "E" => "COB Food/Special App",
                "F" => "COB Premium White",
                "G" => "COB Premium White",
            ],
            "8" => [
                "0" => "Brak soczewki",
                "1" => "Soczewka PMMA",
                "2" => "Soczewka PC",
            ],
            "9" => [
                "G1" => "Szkło hartowane",
                "P1" => "Dyfuzor PMMA",
                "P2" => "Dyfuzor PC",
                "P3" => "Dyfuzor PS",
                "R2" => "Raster PC",
                "S3" => "Reflektor ABS",
                "1" => "transparentny",
                "2" => "Frost",
                "3" => "Opal",
                "4" => "Mikropryzmatyczny",
                "5" => "Połyskliwy",
            ],
            "11" => [
                "00" => "On/Off",
                "A0" => "1-10V",
                "B0" => "Sterowanie przewodowe inne",
                "D0" => "DALI",
                "E3" => "Moduł awaryjny 3h",
                "ED" => "DALI, moduł awaryjny 3h DALI",
                "N0" => "NEMA",
                "S1" => "Czujnik ruchu",
                "Z0" => "Zhaga góra",
                "Z1" => "Zhaga dół",
                "Z2" => "Zhaga podwójna",
            ],
            "13" => "Lat",
            "14" => [
                "0000" => "Brak",
                "BF01" => "Czarny, RAL9005; M. natynkowy",
                "BF02" => "Czarny, RAL9005; M. szyna 3PT",
                "BF03" => "Czarny, RAL9005; M. kinkiet",
                "BL00" => "Czarny, RAL9005; Lewa",
                "BM00" => "Czarny, RAL9005; Środkowa",
                "BR00" => "Czarny, RAL9005; Prawa",
                "W001" => "Biały, RAL9016",
                "G001" => "Szary, RAL7038",
                "B001" => "Czarny, RAL9005",
                "D000" => "Podwójny zasilacz",
                "E001" => "Wersja przelotowa",
                "E002" => "II klasa ochronności",
                "E003" => "Wersja przelotowa; OUT",
                "E004" => "Wersja przelotowa; Szybkozłącze",
                "E005" => "Wersja przelotowa; Zasilanie 3-faz.",
                "E010" => "Bez zasilacza",
                "E012" => "Szybkozłącze",
                "E013" => "Wersja z zasilaczem TR",
                "E014" => "W. z z. TR; II kl. ochr.",
                "E032" => "W. z zasilaczem TRE; II kl. ochr.",
                "E060" => "Wersja z zasilaczem PH1",
                "E062" => "W. z z. PH1; II kl. ochr. 10kV",
                "E100" => "10kV",
                "E102" => "10kV; II klasa ochronności",
                "E200" => "15kV",
                "E300" => "20kV",
                "E302" => "20kV; II klasa ochronności",
                "F001" => "Montaż natynkowy",
                "F002" => "Montaż do szyn 3PT",
                "F003" => "Montaż kinkiet",
                "GF00" => "Szara wersja outdoor",
                "GL00" => "Szary, RAL7038; Montaż natynkowy",
                "GM00" => "Szary, RAL7038; Lewa",
                "GR00" => "Szary, RAL7038; Środkowa",
                "WL00" => "Biały, RAL9016; Lewa",
                "WM00" => "Biały, RAL9016; Środkowa",
                "WR00" => "Biały, RAL9016; Prawa",
                "F004" => "Wersja Outdoor",
                "G002" => "???"
            ]

        ]);


        //English Langugae
        $this->migrator->add('familyCard.en_maping', [
            "HM",
            "HN",
            "HO",
            "HP",
            "HQ",
            "HR"
        ]);
        $this->migrator->add('familyCard.en_translations', [
            "subtitle" => "Advantages",
            "lightspread" => "Luminous flux",
            "configuration" => "Available configurations",
            "example" => "Example product code",
            "under_tables" => "*Depending on the lens used, the luminous flux output may vary, so the table may show luminous flux ranges for a given power version for illustrative purposes.",
            "footer" => "To determine the exact light parameters for the selected beam angle, you need to use the photometric files for the specified beam angle. Download link: <a href=\"https://luxon.pl/Pliki/LDT_LUXON_LED.zip\">LDT-LUXON-LED.zip</a>",
            "name" => "Montage",
        ]);

        $this->migrator->add('familyCard.en_installations', [
            "surface_mounting" => "Surface mounting",
            "pre-wall_installation" => "Recessed-mounted",
            "modular_ceiling_assembly" => "Modular ceiling",
            "special_ceiling_assembly" => "Plaster board",
            "3-phase_rail_mounting" => "3-phase rail",
            "pendant_mounting" => "Suspended",
            "pipe_installation_fi60" => "Pole mounting Ø60",
            "pipe_installation_fi76" => "Pole mounting Ø76"
        ]);

        $this->migrator->add('familyCard.en_columns', [
            1 => "Family",
            2 => "Power version",
            3 => "Version",
            4 => "Power [W]",
            5 => "CRI",
            6 => "Type of diode",
            7 => "CCT [K]",
            8 => "Lens material",
            9 => "Diffuser material",
            10 => "Beam angle",
            11 => "Control",
            12 => "Operating temperature",
            13 => "Warranty",
            14 => "Modifications"
        ]);
        $this->migrator->add('familyCard.en_configurator', [
            "3" => [
                "0" => "Standard",
                "1" => "LIft 1",
                "2" => "LIft 2",
                "8" => "ENCE",
                "9" => "ENEC +",
            ],
            "6" => [
                "A" => "Mid Power",
                "B" => "Highpower",
                "C" => "Highpower 1",
                "D" => "COB",
                "E" => "COB Food/Special App",
                "F" => "COB Premium White",
                "G" => "COB Premium White",
            ],
            "8" => [
                "0" => "No lens",
                "1" => "PMMA lens",
                "2" => "PC lens",
            ],
            "9" => [
                "G1" => "Tempered glass",
                "P1" => "PMMA diffuser",
                "P2" => "PC diffuser",
                "P3" => "PS diffuser",
                "R2" => "PC raster",
                "S3" => "ABS reflector",
                "1" => "Transparent",
                "2" => "Frosted",
                "3" => "Matt",
                "4" => "Microprismatic",
                "5" => "Glossy",
                "00" => "???",
                "0" => "???",
            ],
            "11" => [
                "00" => "On/Off",
                "A0" => "1-10V",
                "B0" => "Other wired controls",
                "D0" => "DALI",
                "E3" => "Emergency module 3h",
                "ED" => "DALI, Emergency module 3h",
                "N0" => "NEMA",
                "S1" => "Motion sensor",
                "Z0" => "Zhaga on the top",
                "Z1" => "Zhaga at the bottom",
                "Z2" => "Zhaga double",
            ],
            "13" => "Years",
            "14" => [
                "0000" => "No modification",
                "BF01" => "Black, RAL9005; Surface mounted",
                "BF02" => "Black, RAL9005; 3-phase track",
                "BF03" => "Black, RAL9005; Wall mounted",
                "BL00" => "Black, RAL9005; Left",
                "BM00" => "Black, RAL9005; Middle",
                "BR00" => "Black, RAL9005; Right",
                "W001" => "White, RAL9016",
                "G001" => "Gray, RAL7038",
                "B001" => "Black, RAL9005",
                "D000" => "Double driver",
                "E001" => "Through-wired",
                "E002" => "II protection class",
                "E003" => "Through-wired; OUT",
                "E004" => "Through-wired; Quick connector",
                "E005" => "Through-wired; 3-phase supply",
                "E010" => "No driver",
                "E012" => "Quick connector",
                "E013" => "TR driver version",
                "E014" => "TR driver version; II protection class",
                "E032" => "TRE driver version; II protection class",
                "E060" => "PH1 driver version",
                "E062" => "PH1 driver version; II protection class 10kV",
                "E100" => "10kV",
                "E102" => "10kV; II protection class",
                "E200" => "15kV",
                "E300" => "20kV",
                "E302" => "20kV; II protection class",
                "F001" => "Surface mounted",
                "F002" => "3-phase track mounted",
                "F003" => "Wall mounted",
                "GF00" => "Gray Outdoor version",
                "GL00" => "Gray, RAL7038; Surface mounted",
                "GM00" => "Gray, RAL7038; Left",
                "GR00" => "Gray, RAL7038; Middle",
                "WL00" => "White, RAL9016; Left",
                "WM00" => "White, RAL9016; Middle",
                "WR00" => "White, RAL9016; Right",
                "F004" => "Outdoor version",
                "G002" => "???"
            ]


        ]);
        //Deutch Langugae
        $this->migrator->add('familyCard.de_maping', [
            "HS",
            "HT",
            "HU",
            "HV",
            "HW",
            "HX"
        ]);
        $this->migrator->add('familyCard.de_translations', [
            "subtitle" => "Vorteile",
            "lightspread" => "Lichtstrom",
            "configuration" => "Verfügbare Konfigurationen",
            "example" => "Beispiel-Produktcode",
            "under_tables" => "*Je nach verwendeter Linse kann die Lichtstromleistung variieren, so dass in der Tabelle zur Veranschaulichung die Lichtstrombereiche für eine bestimmte Leistungsvariante angegeben werden können.",
            "footer" => "Um die genauen Lichtparameter für den ausgewählten Abstrahlwinkel zu ermitteln, müssen die photometrischen Dateien für den angegebenen Abstrahlwinkel verwendet werden. Download-Link: <a href=\"https://luxon.pl/Pliki/LDT_LUXON_LED.zip\">LDT-LUXON-LED.zip</a>",
            "name" => "Montageart",
        ]);

        $this->migrator->add('familyCard.de_installations', [
            "surface_mounting" => "Deckenaufbau",
            "pre-wall_installation" => "Deckeneinbau",
            "modular_ceiling_assembly" => "Modulare Deckenmontage",
            "special_ceiling_assembly" => "Gipskarton",
            "3-phase_rail_mounting" => "3 Phasenschiene",
            "pendant_mounting" => "Seilabhängung",
            "pipe_installation_fi60" => "Pfosten Montage Ø60",
            "pipe_installation_fi76" => "Pfosten Montage Ø76"
        ]);

        $this->migrator->add('familyCard.de_columns', [
            1 => "Familie",
            2 => "Leistungsvariante",
            3 => "Version",
            4 => "Nennleistung [W]",
            5 => "CRI",
            6 => "Typ der Diode",
            7 => "CCT [K]",
            8 => "Material der Linse",
            9 => "Material des Diffusors",
            10 => "Abstrahlwinkel",
            11 => "Lichtsteuerung",
            12 => "Betriebstemperatur [°C]",
            13 => "Garantie",
            14 => "Modifikationen"
        ]);
        $this->migrator->add('familyCard.de_configurator', [
            "3" => [
                "0" => "Standard",
                "1" => "LIft 1",
                "2" => "LIft 2",
                "8" => "ENCE",
                "9" => "ENEC +",
            ],
            "6" => [
                "A" => "Mid Power",
                "B" => "Highpower",
                "C" => "Highpower 1",
                "D" => "COB",
                "E" => "COB Food/Special App",
                "F" => "COB Premium White",
                "G" => "COB Premium White",
            ],
            "8" => [
                "0" => "Keine Linse",
                "1" => "PMMA Linse",
                "2" => "PC Linse",
            ],
            "9" => [
                "G1" => "Gehärtetes Glas",
                "P1" => "PMMA Diffusor",
                "P2" => "PC Diffusor",
                "P3" => "PS Diffusor",
                "R2" => "PC Raster",
                "S3" => "ABS Reflektor",
                "1" => "Transparent",
                "2" => "Frost",
                "3" => "Matt",
                "4" => "Mikroprismatisch",
                "5" => "Glänzend",
                "00" => "???",
                "0" => "???",
            ],
            "11" => [
                "00" => "On/Off",
                "A0" => "1-10V",
                "B0" => "Kabelgebundene Steuerung (andere)",
                "D0" => "DALI",
                "E3" => "Notfall Modul 3h",
                "ED" => "DALI, 3h DALI-Notfall Modul",
                "N0" => "NEMA",
                "S1" => "Bewegungsmelder",
                "Z0" => "Zhaga-Fassung aufwärts",
                "Z1" => "Zhaga-Fassung abwärts",
                "Z2" => "Zhaga-Fassung doppelt",
            ],
            "13" => "Jahres",
            "14" => [
                "0000" => "Keine",
                "BF01" => "Schwarz, RAL9005; M. Aufputz",
                "BF02" => "Schwarz, RAL9005; M. 3PT-Schiene",
                "BF03" => "Schwarz, RAL9005; Wandleuchte",
                "BL00" => "Schwarz, RAL9005; Links",
                "BM00" => "Schwarz, RAL9005; Mitte",
                "BR00" => "Schwarz, RAL9005; Rechts",
                "W001" => "Weiß, RAL9016",
                "G001" => "Grau, RAL7038",
                "B001" => "Schwarz, RAL9005",
                "D000" => "Doppelte Stromversorgung",
                "E001" => "Durchgangsversion",
                "E002" => "Schutzklasse II",
                "E003" => "Durchgangsversion; OUT",
                "E004" => "Durchgangsversion; Schnellanschluss",
                "E005" => "Durchgangsversion; 3PS",
                "E010" => "Ohne Spannungsversorgung",
                "E012" => "Schnellanschluss",
                "E013" => "Version mit Stromversorgung TR",
                "E014" => "V. mit S. TR; Schutzklasse II",
                "E032" => "V. mit S. TRE; Schutzklasse II",
                "E060" => "V. mit Stromversorgung PH1",
                "E062" => "V. mit S. PH1; Schutzklasse II 10kV",
                "E100" => "10kV",
                "E102" => "10kV; Schutzklasse II",
                "E200" => "15kV",
                "E300" => "20kV",
                "E302" => "20kV; Schutzklasse II",
                "F001" => "Aufputzmontage",
                "F002" => "3PT-Schienenmontage",
                "F003" => "Wandmontage",
                "GF00" => "Grau Outdoor Version",
                "GL00" => "Grau, RAL7038; Aufputzmontage",
                "GM00" => "Grau, RAL7038; Links",
                "GR00" => "Grau, RAL7038; Mitte",
                "WL00" => "Weiß, RAL9016; Links",
                "WM00" => "Weiß, RAL9016; Mitte",
                "WR00" => "Weiß, RAL9016; Rechts",
                "F004" => "Outdoor version",
                "G002" => "???"
            ]

        ]);
        $this->migrator->add('familyCard.icons', [
            "logo" => "assets/img/logo.svg",
            'IND' => "assets/img/IND.svg",
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
                    "title" => "ce",
                    'values' => [
                        [
                            "value" => "TAK",
                            'icon' => "assets/img/ce.svg"
                        ]
                    ]
                ],
                [
                    "title" => "outdoor",
                    'values' => [
                        [
                            "value" => "TAK",
                            'icon' => "assets/img/outdoor.png"
                        ]
                    ]
                ],
                [
                    "title" => "indoor",
                    'values' => [
                        [
                            "value" => "TAK",
                            'icon' => "assets/img/indoor.png"
                        ]
                    ]
                ],
                [
                    "title" => "protection",
                    'values' => [
                        [
                            'value' => "I",
                            'icon' => "assets/img/I.png",
                        ],
                        [
                            'value' => "II",
                            'icon' => "assets/img/II.png",
                        ],
                        [
                            'value' => "III",
                            'icon' => "assets/img/III.png"
                        ]
                    ]
                ],
                [

                    "title" => "psu",
                    'values' => [
                        [
                            "value" => "DALI",
                            'icon' => "assets/img/dali.png"
                        ]
                    ]
                ],
            ]

        ]);
    }
};
