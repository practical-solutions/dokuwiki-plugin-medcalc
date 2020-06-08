/* 
 -- Errechnet das aktuelle Schwangerschaftsalter bei bekanntem (errechnetem) Geburtstermin.  --
et = id of Textbox containting [String] Date (as a text) in format dd.mm.yyyy
result = id of object, in which the result should be placed (in none -> return the result (String)
*/
function ga_nach_et(et,result){
    var pattern = /(\d{2})\.(\d{2})\.(\d{4})/;
    st = document.getElementById(et).value;
  
    /* Aktuelles Datum (0:00 Uhr) */
    var now = new Date();
    now.setHours(0);
    now.setMinutes(0);

    /* ET als Datum */
    var et_date = new Date(st.replace(pattern,'$3-$2-$1'));
    et_date.setHours(0);
    et_date.setMinutes(0);

    var timeDiff = now.getTime() - et_date.getTime();
    var diffDays = Math.floor(timeDiff / (1000 * 3600 * 24));
  
    ga = "40+0";
    var spl = ga.split("+");
  
    diffDays = diffDays+(parseInt(spl[0]*7))+parseInt(spl[1]);
  
    if (diffDays<300) {
        var Wochen = Math.floor(diffDays/7);
        var Tage = diffDays % 7;
    } else {
        var Wochen = diffDays;
        var Tage = 0;
    }

    document.getElementById(result).innerHTML = "Aktuelles Gestationsalter: <b>"+Wochen+"+"+Tage+". SSW</b>";  
}

/* 
 -- Aktuelles Gestationsalter nach Letzter Periode errechnen --
lp = id einer Textbox, welches als Text das Datum der letzten Periode im Format dd.mm.yyyy enthält
result = id eines Objekts, in der das Ergebnis reingesetzt wird
*/
function ga_nach_lp(lp,result){
    var pattern = /(\d{2})\.(\d{2})\.(\d{4})/;
    st = document.getElementById(lp).value;
    var lp_data = new Date(st.replace(pattern,'$3-$2-$1'));

    var now = new Date();

    var timeDiff = Math.abs(lp_data.getTime() - now.getTime());
    var diffDays = Math.floor(timeDiff / (1000 * 3600 * 24));
  
    var Wochen = Math.floor(diffDays/7);
    var Tage = diffDays % 7;

    if (timeDiff<0) {
        document.getElementById(result).innerHTML = "Die Letzte Periode muss <u>vor</u> dem heutigen Tage liegen...";
    } else if (isNaN(diffDays)) {
        document.getElementById(result).innerHTML = "Bitte gültiges Datumsformat TT.MM.JJJJ verwenden.";
    } else {
        document.getElementById(result).innerHTML = "Schwangerschaftsalter: "+diffDays+" Tage insgesamt<br> Entspricht der <b>"+Wochen+" + "+Tage+". SSW</b>";
    }
}


/* 
  -- Alter einer Person ausrechnen --
tag, monat, jahr = Alle Parameter sind IDs(!) von TextBoxen(!) in der die benötigten Zahlen als Text drin sind.
result = id des Objekt, in dem das Ergebnis ausgegeben wird.
*/
function alter_berechnen(tag,monat,jahr,result)
{
    var alter = 0;
    var G_tag = parseInt(document.getElementById(tag).value);
    var G_monat = parseInt(document.getElementById(monat).value);
    var G_jahr = parseInt(document.getElementById(jahr).value);

    var G_datum = new Date(G_tag, G_monat, G_jahr);
    var H_datum = new Date();

    var H_tag = parseInt(H_datum.getDate());
    var H_monat = parseInt(H_datum.getMonth())+1;
    var H_jahr = parseInt(H_datum.getFullYear());

    alter = H_jahr - G_jahr;

    if(G_monat > H_monat)
    {
        alter = alter - 1;
	}

    if(G_monat == H_monat)
    {
        if(G_tag > H_tag)
        {
            alter = alter - 1;
        }
    }

    document.getElementById(result).innerHTML = alter + "Jahre"

}

/*
 -- Gestationsalter nach Scheitel-Steiß-Länge ---
*/
function ga_nach_ssl(ssl,result){
    var ssl_data = [0.2, 0.4, 0.5, 1.5, 2.0, 2.5, 3.0, 5.0, 6.0, 7.1, 8.1, 9.4, 10.6, 12.0];

    v = parseFloat((document.getElementById(ssl).value).replace(",","."));
    a = 0;
    for (c=5;c<19;c++) {
        if (ssl_data[c-5]<=v) a=c;
    }
    
    if (isNaN(v)){
        document.getElementById(result).innerHTML = "Bitte gültige Ziffer eingeben.";
    } else if (v>12) {
        document.getElementById(result).innerHTML = "Ab 12 cm muss man die Scheitel-Fersen-Länge bestimmen.";
    } else {
        document.getElementById(result).innerHTML = "Eine SSL von "+v+" cm entspricht"+((a==5)? " <u>max.</u> ":"")+" der <b>"+a+". SSW</b>";
    }
}

/* Gestationsalter nach Gestationsalter (zu anderem Zeitpunkt) */
function ga_nach_ga(g_age,datum,result){
    var now = new Date();

    var pattern = /(\d{2})\.(\d{2})\.(\d{4})/;
    st = document.getElementById(datum).value;
    
    var ga_date = new Date(st.replace(pattern,'$3-$2-$1'));

    var timeDiff = Math.abs(ga_date.getTime() - now.getTime());
    var diffDays = Math.floor(timeDiff / (1000 * 3600 * 24));
  
    ga = document.getElementById(g_age).value;
    var spl = ga.split("+");

    diffDays = diffDays+(parseInt(spl[0]*7))+parseInt(spl[1]);
  
    var Wochen = Math.floor(diffDays/7);
    var Tage = diffDays % 7;

    document.getElementById(result).innerHTML = "Aktuelles Gestationsalter: <b>"+Wochen+"+"+Tage+". SSW</b>";
}

/* KOF und BMI */
function kof_bmi(height,weight,result){

	var h = parseInt(document.getElementById(height).value);
	var w = parseInt(document.getElementById(weight).value);

    var kof = Math.round(0.007184 * Math.pow(h,0.725) * Math.pow(w,0.425)*100)/100;
    var bmi = Math.round( (w / ((h/100)*(h/100)))*10)/10;

    document.getElementById(result).innerHTML = "KOF: "+ kof + " m²<br>BMI: "+bmi+" kg/m²";

}

/* Laufgeschwindigkeit einer Infusion */
function infusion_speed(volumen,zeit,result)
{
	var s = 0;
	var v = parseInt(document.getElementById(volumen).value);
	var t = parseInt(document.getElementById(zeit).value);

    s = v / (t/60);
        
    document.getElementById(result).innerHTML = "Infusionsgeschwindigkeit: "+ s + " ml/h"		
}

/* Medikamentendosis nach KOF */
function medidosis_kof(dosiskof,kofcalc,result) {
	var g = 0;
	var d = parseFloat(document.getElementById(dosiskof).value);

    var k = parseFloat(document.getElementById(kofcalc).value);

    g = d*k;

    document.getElementById(result).innerHTML = "Gesamtdosis: "+ g + " mg"
}

/* Paclitaxel nach AUC */
function paclitaxel_dosis_auc(iauc,igfr,result) {
	var g = 0;
	var auc = parseInt(document.getElementById(iauc).value);
    var gfr = parseInt(document.getElementById(igfr).value);
    g = auc*(gfr+25);
    document.getElementById(result).innerHTML = "Gesamtdosis: "+ g + " mg";
}
