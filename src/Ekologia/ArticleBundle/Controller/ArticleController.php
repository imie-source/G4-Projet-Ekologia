<?php

namespace Ekologia\ArticleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

/**
 * Defines methods to be used into real controllers extending of this one.
 * Before using this class, some methods must be defining
 * (see abstract method documentation for more information).
 */
abstract class ArticleController extends AbstractArticleController
{
    /**
     * Return all articles that have any parent.
     * Used to show a tree format of articles.
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request The current request
     * @return \Ekologia\ArticleBundle\Entity\Article[] The list of articles
     */
    protected function readAll(Request $request) {
        return $this->getDoctrine()
                    ->getRepository($this->getArticleRepositoryName())
                    ->findParents($request);
    }
    
    /**
     * Read an article and return the result of functions defining below.
     * See parameter description to know when it is called.
     * 
     * <pre><code>
     * public function myAction(Request $request, $articleCanonical) {
     * |   return $this->read(
     * |       $request,                       // $request
     * |       $articleCanonical,              // $canonical
     * |       function($request, $article){   // $whenOk
     * |           // Show the page
     * |           return $this->render('myTwig', array('article' => $article));
     * |       },
     * |       function($request, $article){   // $whenForbidden
     * |           // Insert error in flash session and redirect to homepage
     * |           $request->getSession()
     * |                   ->getFlashBag()
     * |                   ->add('error-article-not-found', $article->getId());
     * |           return $this->redirectResponse('homepage');
     * |       },
     * |       function($request, $canonical){ // $whenNotExists
     * |           // Redirect to homepage
     * |           return $this->redirectResponse('homepage');
     * |       }
     * |   );
     * }
     * </code></pre>
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     *      The current request (given by Symfony)
     * @param string $canonical
     *      The article canonical or id
     * @param function $whenOk
     *      Signature: (\Symfony\Component\HttpFoundation\Request, \Ekologia\ArticleBundle\Entity\Article) => mixed<br/>
     *      Called when the article exists and the user can read it.
     * @param function $whenForbidden
     *      Signature: (\Symfony\Component\HttpFoundation\Request, \Ekologia\ArticleBundle\Entity\Article) => mixed<br/>
     *      Called when the article exists but the user cannot read it.
     * @param function $whenNotExists
     *      Signature: (\Symfony\Component\HttpFoundation\Request, string) => mixed<br/>
     *      Called when the article does not exist.
     * @return mixed
     *      The result of one of previous function.
     * @see canRead()
     */
    protected function read(Request $request, $canonical, $whenOk, $whenForbidden, $whenNotExists) {
        $article = $this->getArticle($request, $canonical);
        if (isset($article)) {
            if ($this->canRead($request, $article)) {
                return $whenOk($request, $article);
            } else {
                return $whenForbidden($request, $article);
            }
        } else {
            return $whenNotExists($request, $canonical);
        }
    }
    
    /**
     * Define the article form, validate it and return the result of functions defining below.
     * See parameter description to know when it is called.
     * 
     * <pre><code>
     * public function myAction(Request $request) {
     * |   return $this->create(
     * |       $request,                  // $request
     * |       function($request, $form){ // $whenShow
     * |           // Show the page with the form
     * |           return $this->render('myTwigWithForm', array('form' => $form));
     * |       }
     * |       function($request, $article){   // $whenOk
     * |           // Save the article and redirect to it
     * |           $em = $this->getDoctrine()->getManager();
     * |           $em->persist($article);
     * |           $em->flush();
     * |           return $this->redirectResponse('my-article', array('canonical' => $article->getCanonical());
     * |       },
     * |       function($request, $form){ // $whenBadRequest
     * |           // Show the page with the form - same as $whenOk
     * |           return $this->render('myTwigWithForm', array('form' => $form));
     * |       },
     * |       function($request){  // $whenForbidden
     * |           return $this->redirectResponse('homepage');
     * |       }
     * |   );
     * }
     * </code></pre>
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     *      The current request (given by Symfony)
     * @param function $whenShow
     *      Signature: (\Symfony\Component\HttpFoundation\Request, \Symfony\Component\Form\Form) => mixed<br/>
     *      Called when the user can create article and did not sent data.
     * @param function $whenOk
     *      Signature: (\Symfony\Component\HttpFoundation\Request, \Ekologia\ArticleBundle\Entity\Article) => mixed<br/>
     *      Called when the user can create article, sent data and there is any error.
     * @param function $whenBadRequest
     *      Signature: (\Symfony\Component\HttpFoundation\Request, \Symfony\Component\Form\Form) => mixed<br/>
     *      Called when the user can create article, sent data and there is an error.
     * @param function $whenForbidden
     *      Signature: (\Symfony\Component\HttpFoundation\Request) => mixed<br/>
     *      Called when the user cannot create an article.
     * @return mixed
     *      The result of one of previous function.
     * @see canCreate()
     */
    protected function create(Request $request, $whenShow, $whenOk, $whenBadRequest, $whenForbidden) {
        if ($this->canCreate($request)) {
            $articleClassName = $this->getArticleClassName();
            $articleFormType = $this->getArticleFormType();
            $article = new $articleClassName;
            $form = $this->createForm(new $articleFormType, $article);
            if ($request->getMethod() === 'POST') {
                $form->bind($request);
                if ($form->isValid()) {
                    $article->setCanonical($this->canonicalize($request, $article->getVersion()->getTitle()));
                    $article->addVersion($article->getVersion());
                    $article->setVersion(null);
                    return $whenOk($request, $article);
                } else {
                    return $whenBadRequest($request, $form);
                }
            }
            return $whenShow($request, $form);
        } else {
            return $whenForbidden($request);
        }
    }
    
    /**
     * Define the article form, validate it and return the result of functions defining below.
     * See parameter description to know when it is called.
     * 
     * <pre><code>
     * public function myAction(Request $request, $articleCanonical) {
     * |   return $this->update(
     * |       $request,                       // $request
     * |       $articleCanonical,              // $canonical
     * |       function($request, $form){      // $whenShow
     * |           // Show the page with the form
     * |           return $this->render('myTwigWithForm', array('form' => $form));
     * |       }
     * |       function($request, $article){   // $whenOk
     * |           // Save the article and redirect to it
     * |           $em = $this->getDoctrine()->getManager();
     * |           $em->persist($article);
     * |           $em->flush();
     * |           return $this->redirectResponse('my-article', array('canonical' => $article->getCanonical());
     * |       },
     * |       function($request, $form){      // $whenBadRequest
     * |           // Show the page with the form - same as $whenOk
     * |           return $this->render('myTwigWithForm', array('form' => $form));
     * |       },
     * |       function($request, $article){   // $whenForbidden
     * |           // Insert error in flash session and redirect to homepage
     * |           $request->getSession()
     * |                   ->getFlashBag()
     * |                   ->add('error-article-forbidden', $article->getId());
     * |           return $this->redirectResponse('homepage');
     * |       },
     * |       function($request, $canonical){ // $whenNotExists
     * |           // Redirect to homepage
     * |           return $this->redirectResponse('homepage');
     * |       }
     * |   );
     * }
     * </code></pre>
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     *      The current request (given by Symfony)
     * @param string $canonical
     *      The article canonical or id
     * @param function $whenShow
     *      Signature: (\Symfony\Component\HttpFoundation\Request, \Symfony\Component\Form\Form) => mixed<br/>
     *      Called when the user update the article and did not sent data.
     * @param function $whenOk
     *      Signature: (\Symfony\Component\HttpFoundation\Request, \Ekologia\ArticleBundle\Entity\Article) => mixed<br/>
     *      Called when the user can update the article, sent data and there is any error.
     * @param function $whenBadRequest
     *      Signature: (\Symfony\Component\HttpFoundation\Request, \Symfony\Component\Form\Form) => mixed<br/>
     *      Called when the user can update the article, sent data and there is an error.
     * @param function $whenForbidden
     *      Signature: (\Symfony\Component\HttpFoundation\Request, \Ekologia\ArticleBundle\Entity\Article) => mixed<br/>
     *      Called when the article exists but the user cannot update it.
     * @param function $whenNotExists
     *      Signature: (\Symfony\Component\HttpFoundation\Request, string) => mixed<br/>
     *      Called when the article does not exist.
     * @return mixed
     *      The result of one of previous function.
     * @see canUpdate()
     */
    protected function update(Request $request, $canonical, $whenShow, $whenOk, $whenBadRequest, $whenForbidden, $whenNotExists) {
        $article = $this->getArticle($request, $canonical);
        if (isset($article)) {
            if ($this->canUpdate($request, $article)) {
                $form = $this->createForm(new $this->getArticleFormType(), $article);
                if ($request->getMethod() === 'POST') {
                    $form->bind($request);
                    if ($form->isValid()) {
                        $article->addVersion($article->getVersion());
                        $article->setVersion(null);
                        return $whenOk($request, $article);
                    } else {
                        return $whenBadRequest($request, $form);
                    }
                }
                return $whenShow($request, $form);
            } else {
                return $whenForbidden($request, $article);
            }
        } else {
            return $whenNotExists($request, $canonical);
        }
    }
    
    /**
     * Define the article deletion form, validate it and return the result of functions defining below.
     * See parameter description to know when it is called.
     * 
     * <pre><code>
     * public function myAction(Request $request, $articleCanonical) {
     * |   return $this->remove(
     * |       $request,                       // $request
     * |       $articleCanonical,              // $canonical
     * |       function($request, $form){      // $whenShow
     * |           // Show the page with the form
     * |           return $this->render('myTwigWithForm', array('form' => $form));
     * |       }
     * |       function($request, $article){   // $whenOk
     * |           // Save the article and redirect to homepage
     * |           $em = $this->getDoctrine()->getManager();
     * |           $em->remove($article);
     * |           $em->flush();
     * |           return $this->redirectResponse('homepage');
     * |       },
     * |       function($request, $form){      // $whenBadRequest
     * |           // Show the page with the form - same as $whenOk
     * |           return $this->render('myTwigWithForm', array('form' => $form));
     * |       },
     * |       function($request, $article){   // $whenForbidden
     * |           // Insert error in flash session and redirect to homepage
     * |           $request->getSession()
     * |                   ->getFlashBag()
     * |                   ->add('error-article-forbidden', $article->getId());
     * |           return $this->redirectResponse('homepage');
     * |       },
     * |       function($request, $canonical){ // $whenNotExists
     * |           // Redirect to homepage
     * |           return $this->redirectResponse('homepage');
     * |       }
     * |   );
     * }
     * </code></pre>
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     *      The current request (given by Symfony)
     * @param string $canonical
     *      The article canonical or id
     * @param function $whenShow
     *      Signature: (\Symfony\Component\HttpFoundation\Request, \Symfony\Component\Form\Form) => mixed<br/>
     *      Called when the user can delete the article and did not sent data.
     * @param function $whenOk
     *      Signature: (\Symfony\Component\HttpFoundation\Request, \Ekologia\ArticleBundle\Entity\Article) => mixed<br/>
     *      Called when the user can delete the article, sent data and there is any error.
     * @param function $whenBadRequest
     *      Signature: (\Symfony\Component\HttpFoundation\Request, \Symfony\Component\Form\Form) => mixed<br/>
     *      Called when the user can delete the article, sent data and there is an error.
     * @param function $whenForbidden
     *      Signature: (\Symfony\Component\HttpFoundation\Request, \Ekologia\ArticleBundle\Entity\Article) => mixed<br/>
     *      Called when the article exists but the user cannot delete it.
     * @param function $whenNotExists
     *      Signature: (\Symfony\Component\HttpFoundation\Request, string) => mixed<br/>
     *      Called when the article does not exist.
     * @return mixed
     *      The result of one of previous function.
     * @see canRemove()
     */
    protected function remove(Request $request, $canonical, $whenShow, $whenOk, $whenBadRequest, $whenForbidden, $whenNotExists) {
        $article = $this->getArticle($request, $canonical);
        if (isset($article)) {
            if ($this->canRemove($request, $article)) {
                $form = $this->createForm(new $this->getArticleDeleteFormType(), $article);
                if ($request->getMethod() === 'POST') {
                    $form->bind($request);
                    if ($form->isValid()) {
                        $article->addVersion($article->getVersion());
                        $article->setVersion(null);
                        return $whenOk($request, $article);
                    } else {
                        return $whenBadRequest($request, $form);
                    }
                }
                return $whenShow($request, $form);
            } else {
                return $whenForbidden($request, $article);
            }
        } else {
            return $whenNotExists($request, $canonical);
        }
    }
    
    /**
     * Define the article form, validate it and return the result of functions defining below.
     * See parameter description to know when it is called.
     * 
     * <pre><code>
     * public function myAction(Request $request, $articleCanonical) {
     * |   return $this->update(
     * |       $request,                       // $request
     * |       $articleCanonical,              // $canonical
     * |       function($request, $form){      // $whenShow
     * |           // Show the page with the form
     * |           return $this->render('myTwigWithForm', array('form' => $form));
     * |       }
     * |       function($request, $article){   // $whenOk
     * |           // Save the article and redirect to homepage
     * |           $em = $this->getDoctrine()->getManager();
     * |           $em->remove($article);
     * |           $em->flush();
     * |           return $this->redirectResponse('homepage');
     * |       },
     * |       function($request, $form){      // $whenBadRequest
     * |           // Show the page with the form - same as $whenOk
     * |           return $this->render('myTwigWithForm', array('form' => $form));
     * |       },
     * |       function($request, $article){   // $whenForbidden
     * |           // Insert error in flash session and redirect to homepage
     * |           $request->getSession()
     * |                   ->getFlashBag()
     * |                   ->add('error-article-forbidden', $article->getId());
     * |           return $this->redirectResponse('homepage');
     * |       },
     * |       function($request, $canonical){ // $whenNotExists
     * |           // Redirect to homepage
     * |           return $this->redirectResponse('homepage');
     * |       }
     * |   );
     * }
     * </code></pre>
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     *      The current request (given by Symfony)
     * @param string $canonical
     *      The article canonical or id
     * @param \DateTime $date
     *      The date of the version
     * @param function $whenOk
     *      Signature: (\Symfony\Component\HttpFoundation\Request, \Ekologia\ArticleBundle\Entity\Article) => mixed<br/>
     *      Called when the user can update the article and the given $date is valid.
     * @param function $whenBadRequest
     *      Signature: (\Symfony\Component\HttpFoundation\Request, \Symfony\Component\Form\Form) => mixed<br/>
     *      Called when the user can update the article but the given $date is not valid.
     * @param function $whenForbidden
     *      Signature: (\Symfony\Component\HttpFoundation\Request, \Ekologia\ArticleBundle\Entity\Article) => mixed<br/>
     *      Called when the article exists but the user cannot update it.
     * @param function $whenNotExists
     *      Signature: (\Symfony\Component\HttpFoundation\Request, string) => mixed<br/>
     *      Called when the article does not exist.
     * @return mixed
     *      The result of one of previous function.
     * @see canUpdate()
     */
    protected function backToVersion(Request $request, $canonical, \DateTime $date, $whenOk, $whenBadRequest, $whenForbidden, $whenNotExists) {
        $article = $this->getArticle($request, $canonical);
        if (isset($article)) {
            if ($this->canUpdate($request, $article)) {
                $previousVersion = null;
                foreach ($article->getVersions() as $version) {
                    if ($version->getDateVersion()->getTimestamp() == $date->getTimestamp()) {
                        $previousVersion = $version;
                        break;
                    }
                }
                if (isset($previousVersion)) {
                    $version = clone $previousVersion;
                    $version->setDateVersion(new \DateTime());
                    $article->addVersion($version);
                    return $whenOk($request, $article);
                } else {
                    return $whenBadRequest($request, $article);
                }
            } else {
                return $whenForbidden($request, $article);
            }
        } else {
            return $whenNotExists($request, $canonical);
        }
    }
    
    /**
     * Return the class name of the article form type for creation and modification.
     * 
     * @return string The class name as string
     * @see \Ekologia\ArticleBundle\Form\Type\ArticleFormType
     */
    protected abstract function getArticleFormType();
    
    /**
     * Return the class name of the article form type for deletion.
     * 
     * @return string The class name as string
     */
    protected abstract function getArticleDeleteFormType();
    
    /**
     * Return the class name of the article entity
     * 
     * @return string The class name as string
     * @see \Ekologia\ArticleBundle\Entity\Article
     */
    protected abstract function getArticleClassName();
    
    /**
     * Return the class name of the article version
     * 
     * @return string The class name as string
     * @see \Ekologia\ArticleBundle\Entity\Version
     */
    protected abstract function getVersionClassName();
    
    /**
     * Return the class name of the article repository
     * 
     * @return string The class name as string
     * @see \Ekologia\ArticleBundle\Entity\ArticleRepository
     */
    protected abstract function getArticleRepositoryName();
}
