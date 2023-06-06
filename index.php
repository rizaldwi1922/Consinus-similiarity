<?php
    require_once __DIR__ . '/vendor/autoload.php';

    $stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();
    $stemmer  = $stemmerFactory->createStemmer();
    
    $dokumen1 = "Seorang pemuda setiap hari menjual jamu";
    $dokumen2 = "setiap hari jamu dijual oleh si pemuda";

    $stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();
    

    $output1 = $stemmer->stem($dokumen1);
    $output2 = $stemmer->stem($dokumen2);

     echo $dokumen1."<br>";
     echo $dokumen2."<br><br>";

     echo $output1."<br>";
     echo $output2."<br>";
   

    echo "<br>";

    $term = [];
    $d1 = explode(" ", $output1);
    $d2 = explode(" ", $output2);


    

    foreach ($d1 as $item) {
        array_push($term, $item);
    }

    foreach ($d2 as $item) {
        array_push($term, $item);
    }

    //print_r($term);

 
    
    $term = array_unique($term);
    // echo "<br> TERM Unique <br>";
    // print_r($term);

    //TF D1
    $tf_d1 = [];
    foreach($term as $item){
        $cek1 = false;
        foreach ($d1 as $word) {
            if($item == $word) {
                $cek1 = true;
                $cek = false;
                foreach ($tf_d1 as $i => $k) {
                    if($i == $item){
                        $cek = true;
                        $tf_d1[$i] = $tf_d1[$i] + 1;
                    }
                }
                if(!$cek) {
                    $tf_d1[$item] = 1;
                }
            }
        }
        if(!$cek1){
            $tf_d1[$item] = 0;
        }
    }

    // echo "<br> TF D1<br>";
    // print_r($tf_d1);

    //TF D2
    $tf_d2 = [];
    foreach($term as $item){
        $cek1 = false;
        foreach ($d2 as $word) {
            if($item == $word) {
                $cek1 = true;
                $cek = false;
                foreach ($tf_d2 as $i => $k) {
                    if($i == $item){
                        $cek = true;
                        $tf_d2[$i] = $tf_d2[$i] + 1;
                    }
                }
                if(!$cek) {
                    $tf_d2[$item] = 1;
                }
            }
        }
        if(!$cek1){
            $tf_d2[$item] = 0;
        }
    }

    // echo "<br> TF D2<br>";
    // print_r($tf_d2);


    // echo "<br>";
    // echo "TF Normalisasi <br>";
    $TF_normal_d1 = [];
    foreach ($tf_d1 as $item => $val){
        $TF_normal_d1[$item] = round($val/count($d1),3);
    }

    // echo "normali sasi TF 1 <br>";
    // print_r($TF_normal_d1);
    // echo "<br>";
    

    $TF_normal_d2 = [];
    foreach ($tf_d2 as $item => $val){
        $TF_normal_d2[$item] = round($val/count($d2), 3);
    }

    // echo "normali sasi TF 2 <br>";
    // print_r($TF_normal_d2);
    // echo "<br>";



    //Dokumen frequensi
    $df = [];
    $d1_uniq = array_unique($d1);
    $d2_uniq = array_unique($d2);
    foreach($term as $item){
        foreach($d1_uniq as $k){
            if($item == $k){
                $df[$item] = $df[$item] + 1;
            }
        }

        foreach($d2_uniq as $k){
            if($item == $k){
                $df[$item] = $df[$item] + 1;
            }
        }

    }

    // echo "DF <br>";
    // print_r($df);

    // echo "IDF <br>";
    $idf = [];

    foreach($df as $item => $val){
        //echo $val;
        $idf[$item] = round(log(3/$val, 10), 3);
    }

    // print_r($idf);

    // echo "<br>TF IDF <br>";

    $tf_idf_d1 = [];
    foreach($idf as $key => $val){
        foreach ($TF_normal_d1 as $k => $v){
            if($key == $k){
                $tf_idf_d1[$key] = round($val * $v, 3);
            }
        }
    }
    //echo "<br> TF IDF D1 <br>";
    $a = [];
    foreach($tf_idf_d1 as $key => $val){
        $a[] = $val;
        //echo $key." = ".$val."<br>";
    }

    $b = [];
    $tf_idf_d2 = [];
    foreach($idf as $key => $val){
        foreach ($TF_normal_d2 as $k => $v){
            if($key == $k){
                $tf_idf_d2[$key] = round($val * $v, 3);
            }
        }
    }
  //  echo "<br> TF IDF D2 <br>";
    foreach($tf_idf_d2 as $key => $val){
        $b[] = $val;
        //echo $key." = ".$val."<br>";
    }

    // echo "<br>";
     echo "Cosinus Similiarity";


    //a dan b\
    $pembilang = 0;
    foreach ($a as $i => $val){
        $pembilang = $pembilang + ($val * $b[$i]);
    }
    
    $norm1 = 0;
    $norm2 = 0;

    foreach($a as $val){
        $norm1 = $norm1 + pow($val, 2);
    }

    foreach($b as $val){
        $norm2 = $norm2 + pow($val, 2);
    }

    $hasil = sqrt($norm1) * sqrt($norm2);

    $total = $pembilang / $hasil;
    echo "<br>";
    echo $total;

   

    

    

    
?>