<?php

namespace Rmorillo\JsonGenerator;

class Lorem
{
    private Number $number;
    private string $lorem;

    public function __construct()
    {
        $this->number = new Number;
        $this->lorem = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque id fermentum ligula. Proin feugiat, nulla eget fermentum scelerisque, quam dui auctor sapien, quis luctus mi metus id sapien. Aenean consectetur libero a bibendum aliquet. Vivamus in libero a lorem facilisis tincidunt. Donec mattis eu libero sit amet blandit. Praesent varius rhoncus urna, eu iaculis nulla fermentum nec. Fusce tincidunt scelerisque ante id fermentum. Etiam finibus nec ipsum quis mattis. Sed interdum justo non augue cursus, non tincidunt libero facilisis. Sed eget semper sapien. Nulla facilisi. Sed vehicula facilisis malesuada. Phasellus dictum tincidunt dui, eu convallis ligula venenatis nec. Nullam ac nulla hendrerit, fermentum erat nec, egestas augue. Nulla facilisi. Vivamus iaculis non nunc in mattis. Sed luctus, ante quis fermentum viverra, erat erat tincidunt ex, quis fermentum nulla ligula ut mi. Integer ac nunc elit. Etiam fringilla quis nulla nec tempor. Curabitur bibendum, orci et sollicitudin tincidunt, nulla ligula elementum odio, id sodales purus libero id turpis. Sed sed metus vitae elit iaculis vehicula ut ac eros. Vestibulum vestibulum bibendum est, ut mattis sem viverra id. Vivamus porttitor blandit odio. Nullam nec felis non eros bibendum pharetra. Donec dictum, lectus in bibendum auctor, sapien erat dignissim ipsum, a sodales justo orci non nulla. Nullam feugiat elit sit amet nunc ullamcorper facilisis. Cras vel lacus odio. Nam gravida felis a odio sollicitudin tempus. In hac habitasse platea dictumst. Pellentesque facilisis odio ac sapien sodales feugiat. Pellentesque vestibulum turpis quis urna aliquam, vel vehicula elit pulvinar. Praesent bibendum, quam id dapibus feugiat, nisl urna mattis libero, eu tempus urna neque at sem. Morbi ullamcorper varius libero id vestibulum. Nunc suscipit mattis lorem, at suscipit lectus condimentum et. Fusce sit amet turpis ex. Ut at laoreet quam. Nullam ut purus massa. Maecenas quis nulla vel nulla fringilla sagittis eu a erat. Donec vitae diam luctus, gravida quam a, iaculis nulla. Aliquam erat volutpat. Integer quis nulla eget sem pharetra tincidunt. Fusce vel auctor odio, a tristique dolor. Etiam ultrices at dolor ut volutpat. Suspendisse euismod, enim in vestibulum pharetra, mi ante aliquet est, at scelerisque nisl velit quis odio. Nullam viverra tincidunt ex, vel vulputate turpis tincidunt nec. Nullam lacinia mi non purus tristique, id sagittis nulla sollicitudin. Quisque quis malesuada elit, nec facilisis purus. Nunc quis turpis eget enim lacinia bibendum. Aliquam eu sollicitudin metus, eget semper nulla. Praesent lacinia, tellus quis posuere euismod, elit sem porttitor lectus, ut viverra libero mi ut lorem. Nulla facilisi. Donec dignissim libero nec justo rhoncus, sit amet vestibulum nulla condimentum. Duis a velit mi. Nam a leo sem. Nullam ac nisi sed arcu fringilla facilisis nec a enim. Vivamus ullamcorper bibendum nunc. Integer semper, eros ut sollicitudin blandit, lorem ipsum varius turpis, a tristique nunc ante at arcu. Nam vehicula, enim a tincidunt egestas, nulla lectus aliquam sapien, sit amet dictum mauris turpis eu erat. Vestibulum eget iaculis urna, a vulputate odio. Proin dapibus nisl quis volutpat semper. Quisque malesuada eros a libero fringilla, id malesuada arcu blandit. Vivamus fermentum erat sit amet ligula aliquet, quis volutpat velit eleifend. Ut sodales massa ac urna tincidunt, quis tristique lorem varius. Sed blandit, neque id sodales dictum, odio mauris rhoncus dolor, nec rhoncus neque erat ut leo. Aliquam erat volutpat. Etiam a urna velit. Integer vestibulum ullamcorper nunc, non blandit quam condimentum ac. Suspendisse potenti. Vivamus consectetur a eros a vehicula. Aenean et libero ac enim tempus posuere id sit amet lectus. Fusce sollicitudin ipsum nec justo facilisis, vel interdum turpis cursus. Morbi sagittis libero ac elit efficitur pellentesque. Praesent eget vehicula turpis. Donec aliquet, mi non fermentum ultrices, metus metus accumsan leo, nec vulputate est turpis eu diam. Aenean vel lorem et erat vestibulum vulputate. Vestibulum eleifend hendrerit purus a cursus. Proin id auctor eros. Integer eget velit nec libero vestibulum venenatis. Cras id augue nec libero convallis venenatis. Integer a justo elit.';
    }

    /**
     * @param string $lorem
     * @param int $length
     * @return string
     */
    public function getLoremWords(string $lorem, int $length = 1): string
    {
        $palavras = explode(' ', $lorem);
        $primeirasPalavras = array_slice($palavras, $this->number->integer(1, count($palavras)), $length);
        return ucfirst(strtolower(str_replace(',', '', str_replace('.', '', implode(' ', $primeirasPalavras)))));
    }

    /**
     * @param string $lorem
     * @param int $length
     * @return string
     */
    public function getLoremSentense(string $lorem, int $length = 1): string
    {
        $palavras = explode('.', $lorem);
        $primeirasPalavras = array_slice($palavras, $this->number->integer(1, count($palavras)), $length);
        return implode('.', $primeirasPalavras) . '.';
    }

    /**
     * @param string $lorem
     * @param int $length
     * @return string
     */
    public function getLoremParagraph(string $lorem, int $length = 1): string
    {
        $primeirosParagrafos = [];
        $paragrafos = explode('.', $lorem);
        for ($i = 1; $i <= $length; $i++) {
            for ($j = 1; $j <= 4; $j++) {
                $primeirosParagrafos = array_merge(array_slice($paragrafos, $this->number->integer(1, count($paragrafos)), $length), $primeirosParagrafos);
            }
        }
        return implode('.', $primeirosParagrafos);
    }

    /**
     * @param int $length
     * @param string $type
     * @return string
     */
    public function getLorem(int $length = 1, string $type = 'words'): string
    {
        $lorem = '';
        if ($type === 'sentenses') {
            $lorem = $this->getLoremSentense($this->lorem, $length);
        } elseif ($type === 'paragraphs') {
            $lorem = $this->getLoremParagraph($this->lorem, $length);
        } else {
            $lorem = $this->getLoremWords($this->lorem, $length);
        }
        return trim($lorem);
    }
}
