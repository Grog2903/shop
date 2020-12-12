<?php
/**
 * Created by PhpStorm.
 * User: Kukhtin Gregoriy
 * Date: 28.11.2020
 * Time: 14:34
 */

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class CategoryRender
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entity;
    private $repo;

    public function __construct(EntityManagerInterface $entity)
    {
        $this->entity = $entity;
        $this->repo = $this->entity->getRepository('App:Category');
    }

    public function render()
    {
        $repo = $this->repo;

        $options = array(
            'decorate' => true,
            'rootOpen' => '<ul>',
            'rootClose' => '</ul>',
            'childOpen' => '<li>',
            'childClose' => '</li>',
            'nodeDecorator' => function ($node) {
                if ($node['lvl'] == 0) {
                    return $node['name'];
                } else {
                    return '<a href="/products/category/' . $node['slug'] . '">' . $node['name'] . '</a>';
                }
            }
        );

        return $repo->childrenHierarchy(null, /* starting from root nodes */
            false, /* false: load all children, true: only direct */
            $options
        );
    }

    public function renderE()
    {

        return $this->generate($this->repo->childrenHierarchy(null));
    }

    public function generate($data)
    {
        $i = 0;
        $result = [];
        foreach ($data as $panel){
            $i++;
            echo $panel['name'];
            foreach ($panel['__children'] as $child){
                if($child){
                    echo $child['name'];
                    $this->generate($child);
                }
            }
            if($i == 20){
                continue;
                exit();
            }
        }
    }

    /**
     * @return \Doctrine\Persistence\ObjectRepository|\Gedmo\Tree\Entity\Repository\NestedTreeRepository
     */
    public function getRepo()
    {
        return $this->repo;
    }

}
