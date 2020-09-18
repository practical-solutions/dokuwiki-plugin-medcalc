<?php

// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();

/**
 * medcalc
 *
 * @license  MIT
 * @author   Gero Gothe
 */
class syntax_plugin_medcalc extends DokuWiki_Syntax_Plugin {

    
    public function getType() {
        return 'substition';
    }

    /**
     * Paragraph Type
     */
    public function getPType() {
        return 'block';
    }

    function getSort() { return 136; }

    /**
     * @param string $mode
     */
    public function connectTo($mode) {
        $this->Lexer->addSpecialPattern('\{\{CALC[^\}]*\}\}', $mode, 'plugin_medcalc');
    }

    /**
     * Handler to prepare matched data for the rendering process
     *
     * Handled syntax options:
     *   {{NEWPAGE}}
     *   {{NEWPAGE>your:namespace}}
     *   {{NEWPAGE#newtpl1,newtpl2}}
     *   {{NEWPAGE#newtpl1|Title1,newtpl2|Title1}}
     *   {{NEWPAGE>your:namespace#newtpl1|Title1,newtpl2|Title1}}
     *
     * @param   string       $match   The text matched by the patterns
     * @param   int          $state   The lexer state for the match
     * @param   int          $pos     The character position of the matched text
     * @param   Doku_Handler $handler The Doku_Handler object
     * @return  array Return an array with all data you want to use in render
     * @codingStandardsIgnoreStart
    */
    public function handle($match, $state, $pos, Doku_Handler $handler) {
        
        $t = intval(substr($match,6,-2));
        
        
        return $t;
    }
    

    /**
     * Create the new-page form.
     *
     * @param   $mode     string        output format being rendered
     * @param   $renderer Doku_Renderer the current renderer object
     * @param   $data     array         data created by handler()
     * @return  boolean                 rendered correctly?
     */
    public function render($mode, Doku_Renderer $renderer, $data) {
        global $lang;
        
        if($mode == 'xhtml') {
            if ($data==0 || $data > 9) {
                msg("<b>Bitte einen gültigen Rechner (n=1-5) angeben</b><br><code>{{CALC n}}</code><br><br>
                1: Gestationsalter nach ET<br>
                2: Gestationsalter nach LP<br>
                3: Gestationsalter nach Gestationsalter (zu anderem Zeitpunkt)<br>
                4: Gestationsalter nach SSL<br>
                5: Alter berechnen<br>
                6: BMI und KOF berechnen<br>
                7: Laufgeschwindigkeit einer Infusion<br>
                8: Medikamentendosis nach KOF<br>
                9: Paclitaxeldosis nach AUC");
                return true;
            }
            
            $renderer->doc .= '<div class="plugin__medcalc_box">';
            
            switch ($data){
                case 1: $r = '<h3>Gestationsalter - Berechnung aus Entbindungstermin</h3>
                              Datum: <input id="plugin__medcalc_et" type="text" placeholder="TT.MM.JJJJ" style="width:150px"><br>
                              <input type="button" value="Berechnen" onclick="ga_nach_et(\'plugin__medcalc_et\',\'plugin__medcalc_ga_nach_et\');">
                              <div id="plugin__medcalc_ga_nach_et" style="color:blue;"></div>';
                              break;

                case 2: $r = '<h3>Gestationsalter - Berechnung aus Letzter Periode</h3>
                              Letzte Periode: <Input id="plugin__medcalc_lp" style="width:150px" type="text" placeholder="TT.MM.JJJJ"><br> 
                              <Input type="button" value="Berechnen" onclick="ga_nach_lp(\'plugin__medcalc_lp\',\'plugin_medcalc_ga_nach_lp\');">
                              <div id="plugin_medcalc_ga_nach_lp" style="color:blue;"></div>';
                              break;

                case 3: $r = '<h3>Gestationsalter - Berechnung aus bekanntem Gestationsalter zu anderem Zeitpunkt</h3>
                              Datum: <Input style="width:150px" id="plugin__medcalc_gaga" type="text" placeholder="TT.MM.JJJJ"><br>
                              Gestationsalter (SSW) : <Input style="width:150px" id="plugin__medcalc_gaga_alter" type="text" placeholder="X+Y"><br>
                              <Input type="button" value="Berechnen" onclick="ga_nach_ga(\'plugin__medcalc_gaga_alter\',\'plugin__medcalc_gaga\',\'plugin__medcalc_gaga_result\');">
                              <div id="plugin__medcalc_gaga_result" style="color:blue;"></div>';
                              break;

                case 4: $r = '<h3>Gestationsalter - Berechnung aus SSL</h3>
                              Bis zur 18. SSW, möglich (dann Scheitel-Fersen-Länge). <br><br>
                              SSL (in cm): <input type="text" style="width:150px" id="plugin__medcalc_ssl"><br>
                              <input type="button" value="Berechnen" onclick="ga_nach_ssl(\'plugin__medcalc_ssl\',\'plugin__medcalc_ssl_nach_ga\');">
                              <div id="plugin__medcalc_ssl_nach_ga" style="color:blue;"></div>';
                              break;
                              
                case 5: $r = '<h3>Alter berechnen</h3>Geburtsdatum:
                              <Input id="plugin__medcalc_TAG" type="text" placeholder="Tag" style="width:80px"> 
                              <Input id="plugin__medcalc_MONAT" type="text" placeholder="Monat" style="width:80px">
                              <Input id="plugin__medcalc_JAHR" type="text" placeholder="Jahr" style="width:80px">
                              <Input type="button" value="Berechnen" onclick="alter_berechnen(\'plugin__medcalc_TAG\',\'plugin__medcalc_MONAT\',\'plugin__medcalc_JAHR\',\'plugin__medcalc_pat_alter\');"> <br>
                              <div id="plugin__medcalc_pat_alter" style="color:blue;"></div>';
                              break;

                case 6: $r = '<h3>Körperoberfläche und Body-Mass-Index berechnen</h3>
                              Größe: <Input id="plugin__medcalc_HEIGHT" type="text" placeholder="in cm" style="width:100px">
                              Gewicht: <Input id="plugin__medcalc_WEIGHT" type="text" placeholder="in kg" style="width:100px">
                              <Input type="button" value="Berechnen" onclick="kof_bmi(\'plugin__medcalc_HEIGHT\',\'plugin__medcalc_WEIGHT\',\'plugin__medcalc_KOF\');"> <br>
                              <div id="plugin__medcalc_KOF" style="color:blue;"></div>';
                              break;

                case 7: $r = '<h3>Infusionsgeschwindigkeit berechnen</h3>
                              Volumen: <Input id="plugin__medcalc_VOLUMEN" type="text" placeholder="in ml" style="width:100px"> 
                              Infusionsdauer: <Input id="plugin__medcalc_TIME" type="text" placeholder="in min" style="width:100px"> 
                              <Input type="button" value="Berechnen" onclick="infusion_speed(\'plugin__medcalc_VOLUMEN\',\'plugin__medcalc_TIME\',\'plugin__medcalc_SPEED\');"> <br>
                              <div id="plugin__medcalc_SPEED" style="color:blue;"></div>';
                              break;

                case 8: $r = '<h3>Medikamentendosis nach Körperoberfäche</h3>
                              Körberoberfläche: <Input id="medcalc__KOF1" type="text" placeholder="in m²" style="width:100px">
                              Dosis in mg/m² KOF: <Input id="medcalc__DOSISKOF" type="text" placeholder="in mg/m²" style="width:100px">
                              <Input type="button" value="Berechnen" onclick="medidosis_kof(\'medcalc__KOF1\',\'medcalc__DOSISKOF\',\'medcalc__dosis1\');">
                              <div id="medcalc__dosis1" style="color:blue;"></div>';
                              break;
                              
                case 9: $r = '<h3>Paclitaxeldosis nach AUC</h3>
                              AUC: <Input id="medcalc__AUC" type="text" placeholder="" style="width:100px">
                              GFR: <Input id="medcalc__GFR" type="text" placeholder="in ml/min" style="width:100px">
                              <Input type="button" value="Berechnen" onclick="paclitaxel_dosis_auc(\'medcalc__AUC\',\'medcalc__GFR\',\'medcalc__dosis2\');">
                              <div id="medcalc__dosis2" style="color:blue;"></div>';
                              break;

            }
            
            $renderer->doc .= $r;
            
            $renderer->doc .= '</div>';
            return true;
        }

        return false;
    }
}