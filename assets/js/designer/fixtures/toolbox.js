/*
 * SR - 2013-07-01
 * - Added "displayname" parameter for localisation in German.
 *   "name" cannot be changed for certain parameters, due to
 *   internal use in source code (eg. width, size, colour).
 *
 *  - Translated display names & categories into German
 *  - Added description field for every element
 * 
 * SR - 2013-07-08
 * - Added labels to each element (toolbox members -> "label" attribute)
 * 
 * SR - 2013-08-04
 * - Set some "text" fields as type "String" -> displayed in textarea
 * 
 */

usemockups.fixtures.toolbox = [
    {
        "name":"button",
        "label":"Button",
        "category":"Formular",
        "template":"#button-template",
        "attributes":[
            {
                "displayname":"Beschriftung",
                "name":"text",
                "default":"Button"
            },
            {
                "displayname":"Breite",
                "name":"width",
                "default":80
            }
            //SR - 2013-07-01
            ,
            {
                "displayname":"Beschreibung",
                "name":"description",
                "type": "string"
            }
            //~SR
        ]
    },
    {
        "name":"checkbox",
        "label":"Kontrollbox",
        "category":"Formular",
        "template":"#checkbox-template",
        "attributes":[
            {
                "displayname":"Aktiviert",
                "name":"checked",
                "type":"boolean",
                "default":true
            },
            {
                "displayname":"Text",
                "name":"text",
                "default":"Label"
            }
            //SR - 2013-07-01
            ,
            {
                "displayname":"Beschreibung",
                "name":"description",
                "type": "string"
            }
            //~SR
        ]
    },
    {
        "name":"combobox",
        "label":"Dropdown-Liste",
        "category":"Formular",
        "template":"#combobox-template",
        "attributes":[
            {
                "displayname":"Text",
                "name":"text",
                "default":"Combobox"
            },
            {
                "displayname":"Breite",
                "name":"width",
                "default":120
            }
            //SR - 2013-07-01
            ,
            {
                "displayname":"Beschreibung",
                "name":"description",
                "type": "string"
            }
            //~SR
        ]
    },
    {
        "name":"radio",
        "label":"Optionsfeld",
        "category":"Formular",
        "template":"#radio-template",
        "attributes":[
            {
                "displayname":"Aktiviert",
                "name":"checked",
                "type":"boolean",
                "default":true
            },
            {
                "displayname":"Text",
                "name":"text",
                "default":"Label"
            }
            //SR - 2013-07-01
            ,
            {
                "displayname":"Beschreibung",
                "name":"description",
                "type": "string"
            }
            //~SR
        ]
    },
    {
        "name":"text",
        "label":"Textfeld",
        "category":"Formular",
        "template":"#text-template",
        "min_height":20,
        "attributes":[
            {
                "displayname":"Beschriftung?",
                "name":"label",
                "type":"boolean",
                "default":true
            },
            {
                "displayname":"Text",
                "name":"text",
                "default":"Label"
            },
            {
                "displayname":"Breite",
                "name":"width",
                "default":200
            },
            {
                "displayname":"H&ouml;he",
                "name":"height",
                "default":30
            }
            //SR - 2013-07-01
            ,
            {
                "displayname":"Beschreibung",
                "name":"description",
                "type": "string"
            }
            //~SR
        ]
    },
    {
        "name":"heading",
        "label":"&Uuml;berschrift",
        "category":"Schrift",
        "template":"#heading-template",
        "attributes":[
            {
                "displayname":"Text",
                "name":"text",
                "default":"&Uuml;berschrift"
            },
            {
                "displayname":"Schriftgr&ouml;sse",
                "name":"size",
                "default":"25"
            },
            {
                "displayname":"Farbe",
                "name":"color",
                "default":"black"
            }
            //SR - 2013-07-01
            ,
            {
                "displayname":"Beschreibung",
                "name":"description",
                "type": "string"
            }
            //~SR
        ]
    },

    {
        "name":"paragraph",
        "label":"Absatz",
        "category":"Schrift",
        "template":"#paragraph-template",
        "attributes":[
            {
                "displayname":"Text",
                "name":"text",
                "default":"Absatztext",
                "type": "string"
            },
            {
                "displayname":"Breite",
                "name":"width",
                "default":300
            },
            {
                "displayname":"Hintergrundfarbe",
                "name":"background",
                "default":"#dedede"
            }
            //SR - 2013-07-01
            ,
            {
                "displayname":"Beschreibung",
                "name":"description",
                "type": "string"
            }
            //~SR
        ]
    },

    {
        "name":"label",
        "label":"Beschriftung",
        "category":"Schrift",
        "template":"#label-template",
        "attributes":[
            {
                "displayname":"Text",
                "name":"text",
                "default":"Label"
            },
            {
                "displayname":"Schriftgr&ouml;sse",
                "name":"size",
                "default": 18
            },
            {
                "displayname":"Farbe",
                "name":"color",
                "default":"#383838"
            }
            //SR - 2013-07-01
            ,
            {
                "displayname":"Beschreibung",
                "name":"description",
                "type": "string"
            }
            //~SR
        ]
    },

    {
        "name":"table",
        "label":"Tabelle",
        "category":"Inhalt",
        "template":"#table-template",
        "attributes":[
            {
                "displayname":"Breite",
                "name":"width",
                "default":250
            },
            {
                "displayname":"Zeilen",
                "name":"rows",
                "default":"3"
            },
            {
                "displayname":"Spalten",
                "name":"columns",
                "default":"3"
            },
            {
                "displayname":"Werte",
                "name":"values",
                "hidden":true,
                "default":[
                    ["", "", ""],
                    ["", "", ""],
                    ["", "", ""]
                ]
            }
            //SR - 2013-07-01
            ,
            {
                "displayname":"Beschreibung",
                "name":"description",
                "type": "string"
            }
            //~SR
        ]
    },

    {
        "name":"image",
        "label":"Bild (Platzhalter)",
        "category":"Inhalt",
        "template":"#image-template",
        "attributes":[
            {
                "displayname":"Breite",
                "name":"width",
                "default":250
            },
            {
                "displayname":"H&ouml;he",
                "name":"height",
                "default":250
            }
            //SR - 2013-07-01
            ,
            {
                "displayname":"Beschreibung",
                "name":"description",
                "type": "string"
            }
            //~SR
        ]
    },

    {
        "name":"rectangle",
        "label":"Rechteck",
        "category":"Figuren",
        "template":"#shape-template",
        "min_height":1,
        "min_width":1,
        "attributes":[
            {
                "displayname":"Breite",
                "name":"width",
                "default":250
            },
            {
                "displayname":"H&ouml;he",
                "name":"height",
                "default":100
            },
            {
                "displayname":"Farbe",
                "name":"color",
                "default":"#dedede"
            },
            {
                "displayname":"Rahmen",
                "name":"border",
                "default":true,
                "type":"boolean"
            }
            //SR - 2013-07-01
            ,
            {
                "displayname":"Beschreibung",
                "name":"description",
                "type": "string"
            }
            //~SR
        ]
    },

    {
        "name":"circle",
        "label":"Kreis",
        "category":"Figuren",
        "template":"#shape-template",
        "attributes":[
            {
                "displayname":"Breite",
                "name":"width",
                "default":200
            },
            {
                "displayname":"H&ouml;he",
                "name":"height",
                "default":200
            },
            {
                "displayname":"Farbe",
                "name":"color",
                "default":"#dedede"
            },
            {
                "displayname":"Rahmen",
                "name":"border",
                "default":true,
                "type":"boolean"
            }
            //SR - 2013-07-01
            ,
            {
                "displayname":"Beschreibung",
                "name":"description",
                "type": "string"
            }
            //~SR
        ]
    }


];