<?php

namespace Ekologia\ArticleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Ekologia\MainBundle\Controller\MasterController;

/**
 * Abstract controller used by all controllers in this bundle.
 * Defines somes usefull methods usable into children classes.
 */
abstract class AbstractArticleController extends MasterController
{
    /**
     * Get the article in terms of its canonical or id.
     * 
     * @param string $canonical The article canonical or id
     * @return \Ekologia\ArticleBundle\Entity\Article
     */
    protected function getArticle($canonical) {
        $rep = $this->getDoctrine()->getRepository($this->getArticleRepositoryName());
        $article = $rep->findBy(array('canonical' => $canonical));
        return $article === null
               ? $rep->find($canonical) // with id
               : $article;
    }
    
    /**
     * Create a canonical in terms of given string.
     * The canonical is a string which is similar with the given string, but accepted by URL.
     * 
     * @param string $title
     * @return string
     */
    protected function canonicalize($title) {
        $unwanted_array = array('Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
                                'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
                                'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
                                'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
                                'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'Ğ'=>'G', 'İ'=>'I', 'Ş'=>'S', 'ğ'=>'g', 'ı'=>'i',
                                'ş'=>'s', 'ü'=>'u', 'ă'=>'a', 'Ă'=>'A', 'ș'=>'s', 'Ș'=>'S', 'ț'=>'t', 'Ț'=>'T', '@' => 'a',
                                ' ' => '-', "\t" => '-', "\n" => '-', '_' => '-');
        $wanted_array = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '-');
        $tmp = strtr($title, $unwanted_array);
        $tmp = strtolower($tmp);
        $result = '';
        foreach (str_split($tmp) as $char) {
            if (in_array($char, $wanted_array)) {
                $result .= $char;
            }
        }
        do {
            $result = str_replace('--', '-', $result, $count);
        } while ($count > 0);
        
        if ($this->getArticle($result) != null) {
            $i = 2;
            do {
                $tmp = $result . '-' . $i;
                $i++;
            } while ($this->getArticle($tmp) != null);
            $result = $tmp;
        }
        return $result;
    }
    
    /**
     * Check if the user can read the given element.
     * 
     * @param Request $request The current request, given by Symfony
     * @param mixed $element   The element to read (article, comment or other element)
     */
    protected abstract function canRead(Request $request, $element);
    
    /**
     * Check if the user can create an element.
     * 
     * @param Request $request The current request, given by Symfony
     */
    protected abstract function canCreate(Request $request);
    
    /**
     * Check if the user can update the given element.
     * 
     * @param Request $request The current request, given by Symfony
     * @param mixed $element   The element to update (article, comment or other element)
     */
    protected abstract function canUpdate(Request $request, $element);
    
    /**
     * Check if the user can remove the given element.
     * 
     * @param Request $request The current request, given by Symfony
     * @param mixed $element   The element to remove (article, comment or other element)
     */
    protected abstract function canRemove(Request $request, $element);
}
