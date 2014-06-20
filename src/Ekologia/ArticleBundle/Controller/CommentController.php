<?php

namespace Ekologia\ArticleBundle\Controller;

abstract class CommentController extends AbstractArticleController
{
    /**
     * Read comments from an article and return the result of functions defining below.
     * See parameter description to know when it is called.
     * 
     * <pre><code>
     * public function myAction(Request $request, $articleCanonical) {
     * |   return $this->read(
     * |       $request,                       // $request
     * |       $articleCanonical,              // $canonical
     * |       function($request, $comments){   // $whenOk
     * |           // Show the page
     * |           return $this->render('myTwig', array('comments' => $comments));
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
     *      Signature: (\Symfony\Component\HttpFoundation\Request, \Ekologia\ArticleBundle\Entity\Commentaire[]) => mixed<br/>
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
    public function readFromArticle(Request $request, $canonical, $whenOk, $whenForbidden, $whenNotExists) {
        $article = $this->getArticle($request, $canonical);
        if (isset($article)) {
            if ($this->canRead($request, $article)) {
                $comments = $this->getDoctrine()
                                 ->getRepository($this->getCommentRepositoryName())
                                 ->findBy(array('article' => $article), array('date' => 'desc'));
                return $whenOk($request, $comments);
            } else {
                return $whenForbidden($request, $article);
            }
        } else {
            return $whenNotExists($request, $canonical);
        }
    }
    
    /**
     * Define the comment form, validate it and return the result of functions defining below.
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
     * |       function($request, $comment){   // $whenOk
     * |           // Save the article and redirect to it
     * |           $em = $this->getDoctrine()->getManager();
     * |           $em->persist($comment);
     * |           $em->flush();
     * |           return $this->redirectResponse('my-article', array('canonical' => $comment->getArticle()->getCanonical());
     * |       },
     * |       function($request, $form){ // $whenBadRequest
     * |           // Show the page with the form - same as $whenOk
     * |           return $this->render('myTwigWithForm', array('form' => $form));
     * |       },
     * |       function($request, $article){  // $whenForbidden
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
     * @param function $whenShow
     *      Signature: (\Symfony\Component\HttpFoundation\Request, \Symfony\Component\Form\Form) => mixed<br/>
     *      Called when the user can create comment and did not sent data.
     * @param function $whenOk
     *      Signature: (\Symfony\Component\HttpFoundation\Request, \Ekologia\ArticleBundle\Entity\Comment) => mixed<br/>
     *      Called when the user can create comment, sent data and there is any error.
     * @param function $whenBadRequest
     *      Signature: (\Symfony\Component\HttpFoundation\Request, \Symfony\Component\Form\Form) => mixed<br/>
     *      Called when the user can create comment, sent data and there is an error.
     * @param function $whenForbidden
     *      Signature: (\Symfony\Component\HttpFoundation\Request, \Ekologia\ArticleBundle\Entity\Article) => mixed<br/>
     *      Called when the article exists but the user cannot comment it.
     * @param function $whenNotExists
     *      Signature: (\Symfony\Component\HttpFoundation\Request, string) => mixed<br/>
     *      Called when the article does not exist.
     * @return mixed
     *      The result of one of previous function.
     * @see canCreate()
     */
    public function create(Request $request, $canonical, $whenShow, $whenOk, $whenBadRequest, $whenForbidden, $whenNotExists) {
        $article = $this->getArticle($request, $canonical);
        if (isset($article)) {
            if ($this->canCreate($request, $article)) {
                $comment = new $this->getCommentClassName();
                $form = $this->createForm(new $this->getCommentFormType(), $comment);
                if ($request->getMethod() === 'POST') {
                    $form->bind($request);
                    if ($form->isValid()) {
                        $comment->setArticle($article);
                        return $whenOk($request, $comment);
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
     * Define the comment form, validate it and return the result of functions defining below.
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
     * |           $em->persist($comment);
     * |           $em->flush();
     * |           return $this->redirectResponse('my-article', array('canonical' => $article->getCanonical());
     * |       },
     * |       function($request, $form){      // $whenBadRequest
     * |           // Show the page with the form - same as $whenOk
     * |           return $this->render('myTwigWithForm', array('form' => $form));
     * |       },
     * |       function($request, $comment){   // $whenForbidden
     * |           // Insert error in flash session and redirect to homepage
     * |           $request->getSession()
     * |                   ->getFlashBag()
     * |                   ->add('error-article-forbidden', $comment->getId());
     * |           return $this->redirectResponse('homepage');
     * |       },
     * |       function($request, $id){ // $whenNotExists
     * |           // Redirect to homepage
     * |           return $this->redirectResponse('homepage');
     * |       }
     * |   );
     * }
     * </code></pre>
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     *      The current request (given by Symfony)
     * @param string $id
     *      The comment id
     * @param function $whenShow
     *      Signature: (\Symfony\Component\HttpFoundation\Request, \Symfony\Component\Form\Form) => mixed<br/>
     *      Called when the user update the comment and did not sent data.
     * @param function $whenOk
     *      Signature: (\Symfony\Component\HttpFoundation\Request, \Ekologia\ArticleBundle\Entity\Comment) => mixed<br/>
     *      Called when the user can update the comment, sent data and there is any error.
     * @param function $whenBadRequest
     *      Signature: (\Symfony\Component\HttpFoundation\Request, \Symfony\Component\Form\Form) => mixed<br/>
     *      Called when the user can update the comment, sent data and there is an error.
     * @param function $whenForbidden
     *      Signature: (\Symfony\Component\HttpFoundation\Request, \Ekologia\ArticleBundle\Entity\Comment) => mixed<br/>
     *      Called when the comment exists but the user cannot update it.
     * @param function $whenNotExists
     *      Signature: (\Symfony\Component\HttpFoundation\Request, int) => mixed<br/>
     *      Called when the comment does not exist.
     * @return mixed
     *      The result of one of previous function.
     * @see canUpdate()
     */
    public function update(Request $request, $id, $whenShow, $whenOk, $whenBadRequest, $whenForbidden, $whenNotExists) {
        $comment = $this->getDoctrine()->getRepository($this->getCommentRepositoryName())->find($id);
        if (isset($comment)) {
            if ($this->canUpdate($request, $comment)) {
                $form = $this->createForm(new $this->getCommentFormType(), $comment);
                if ($request->getMethod() === 'POST') {
                    $form->bind($request);
                    if ($form->isValid()) {
                        return $whenOk($request, $comment);
                    } else {
                        return $whenBadRequest($request, $form);
                    }
                }
                return $whenShow($request, $form);
            } else {
                return $whenForbidden($request, $comment);
            }
        } else {
            return $whenNotExists($request, $id);
        }
    }
    
    /**
     * Define the comment deletion form, validate it and return the result of functions defining below.
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
     * @param string $id
     *      The comment id
     * @param function $whenShow
     *      Signature: (\Symfony\Component\HttpFoundation\Request, \Symfony\Component\Form\Form) => mixed<br/>
     *      Called when the user can delete the comment and did not sent data.
     * @param function $whenOk
     *      Signature: (\Symfony\Component\HttpFoundation\Request, \Ekologia\ArticleBundle\Entity\Comment) => mixed<br/>
     *      Called when the user can delete the comment, sent data and there is any error.
     * @param function $whenBadRequest
     *      Signature: (\Symfony\Component\HttpFoundation\Request, \Symfony\Component\Form\Form) => mixed<br/>
     *      Called when the user can delete the comment, sent data and there is an error.
     * @param function $whenForbidden
     *      Signature: (\Symfony\Component\HttpFoundation\Request, \Ekologia\ArticleBundle\Entity\Comment) => mixed<br/>
     *      Called when the comment exists but the user cannot delete it.
     * @param function $whenNotExists
     *      Signature: (\Symfony\Component\HttpFoundation\Request, string) => mixed<br/>
     *      Called when the comment does not exist.
     * @return mixed
     *      The result of one of previous function.
     * @see canRemove()
     */
    public function remove(Request $request, $id, $whenShow, $whenOk, $whenBadRequest, $whenForbidden, $whenNotExists) {
        $comment = $this->getDoctrine()->getRepository($this->getCommentRepositoryName())->find($id);
        if (isset($comment)) {
            if ($this->canRemove($request, $comment)) {
                $form = $this->createForm(new $this->getCommentDeleteFormType(), $comment);
                if ($request->getMethod() === 'POST') {
                    $form->bind($request);
                    if ($form->isValid()) {
                        return $whenOk($request, $comment);
                    } else {
                        return $whenBadRequest($request, $form);
                    }
                }
                return $whenShow($request, $form);
            } else {
                return $whenForbidden($request, $comment);
            }
        } else {
            return $whenNotExists($request, $id);
        }
    }
    
    /**
     * Return the class name of the comment entity
     * 
     * @return string The class name as string
     * @see \Ekologia\ArticleBundle\Entity\Commentaire
     */
    public abstract function getCommentClassName();
    
    /**
     * Return the class name of the comment form type for creation and modification.
     * 
     * @return string The class name as string
     * @see \Ekologia\ArticleBundle\Form\Type\CommentFormType
     */
    protected abstract function getCommentFormType();
    
    /**
     * Return the class name of the comment form type for deletion.
     * 
     * @return string The class name as string
     */
    protected abstract function getCommentDeleteFormType();
    
    /**
     * Return the class name of the article repository
     * 
     * @return string The class name as string
     * @see \Ekologia\ArticleBundle\Entity\ArticleRepository
     */
    protected abstract function getArticleRepositoryName();
    
    /**
     * Return the class name of the comment repository
     * 
     * @return string The class name as string
     * @see \Ekologia\ArticleBundle\Entity\CommentaireRepository
     */
    protected abstract function getCommentRepositoryName();
}
