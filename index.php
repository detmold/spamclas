<?php 
class Spam {
    public $weight;
    public $bias;
    public $minX;
    public $maxX;


    public function init() {
        $ilosc_testow = stream_get_line(STDIN, 20000000000, PHP_EOL);
        for ($k = 0; $k < $ilosc_testow; $k++) {
            $testData = explode(' ', stream_get_line(STDIN, 2000000000, PHP_EOL));
            $n = $testData[0];  //2
            $this->minX = $testData[1];  //1
            $this->maxX = $testData[2];  //4
            
            $parity = array();
            $a = 0;
            $b = 1;
            /**
             * Robimy petle po $n czyli ilosci neuronow a nie po przedziale liczb <$this->minX, $this->maxX>
             * de facto obchodzi nas tylko ostatnie przeksztalcenie ale zeby wynik dla niego byl wlasciwy musimy porobic wszystkie poprzednie
             * Wzor na przeksztalcenie i-tego neuronu wyglada nastepujaco: yi = wi * xi + bi, gdzie wi i bi są znane
             * $a i $b to flagi wskazujące czy dostarczamy na wejscie liczbe parzysta - $a czy nieparzysta - $b
             * to co ostatecznie uzyskujemy to wiadomosc czy ostatnie przeksztalcenie 
             * przy startowej liczbie parzystej ($a=0) przynioslo ostatecznie liczbe parzysta czy nieparzysta
             * oraz to czy ostatnie przeksztalcenie przy startowej liczbie nieparzystej ($b=0) przynioslo ostatecznie liczbe parzysta czy nieparzysta
             * 
             * jesli w obydwu przypadkach otrzymalismy liczby parzyste to znaczy ze wszyscy byli nie spamujacy 
             * 
             * jesli w obydwu przypadkach otrzymalismy liczby nieparzyste to znaczy ze 
             * wszyscy byli spamujacy => $sp = $len - $non_sp; gdzie $non_sp = 0
             * 
             * jesli w jednym z przypadkow otrzymalismy liczbe parzysta dla startujacej liczba parzystej ($a=0) 
             * oraz liczbe nieparzysta dla startujacej liczby nieparzystej ($b=1)
             * 
             * to znaczy ze nie spamujacych bedzie tyle ile w przedziale <$this->minX, $this->maxX> jest liczb parzystych 
             * oraz ze spamujacych bedzie: wielosc_przedzialu - ilosc_nie_spamujacych
             * 
             * jesli w jednym z przypadkow otrzymalismy liczbe nieparzysta dla startujacej liczba parzystej ($a=0) 
             * oraz liczbe parzysta dla startujacej liczby nieparzystej ($b=1)
             * to znaczy ze nie spamujacych bedzie tyle ile w przedziale <$this->minX, $this->maxX> jest liczb nieparzystych 
             * oraz ze spamujacych bedzie: wielosc_przedzialu - ilosc_nie_spamujacych
             */
            for ($i = 0; $i < $n; $i++) {
                $neuronData = explode(' ', stream_get_line(STDIN, 10000000000, PHP_EOL));
                $this->weight = $neuronData[0];  //1
                $this->bias = $neuronData[1];  //2
                $a = ($this->weight * $a + $this->bias)%2;  //parzyste
                $b = ($this->weight * $b + $this->bias)%2;  //nieparzyste    
            }
            $parity[0] = $a;
            $parity[1] = $b;

            $sp = 0;
            $non_sp = 0;
            $len = $this->maxX - $this->minX + 1;

            // jesli ostatnia wygenerowana cyfra przez siec neuronowa startujac 
            // od liczby parzystej byla parzysta to wchodzimy w ponizszy warunek
            // w warunku obliczamy ilosc nie spamujacych czyli tych dla ktorych wygenerowane byly liczby parzyste
            if($parity[$this->minX % 2] == 0) {
                $non_sp = floor(($this->maxX - $this->minX) / 2) + 1;
            }
            // jesli ostatnia wygenerowana cyfra przez siec neuronowa startujac 
            //od liczby nieparzystej byla parzysta to wchodzimy w ponizszy warunek
            // w warunku obliczamy ilosc nie spamujacych czyli tych dla ktorych wygenerowane byly liczby parzyste
            if($parity[(($this->minX + 1) % 2)] == 0) {
                $non_sp += floor(($this->maxX - $this->minX - 1) / 2) + 1;
            }
            // liczbe spamujaych czyli tych dla ktorych wygenerowane zostaly liczby nieparzyste obliczamy jako calosc - liczbe spamujacych
            $sp = $len - $non_sp;

            echo $non_sp . ' ' . $sp . PHP_EOL;
        }
    }
    
}

$spam = new Spam();
$spam->init();

?>