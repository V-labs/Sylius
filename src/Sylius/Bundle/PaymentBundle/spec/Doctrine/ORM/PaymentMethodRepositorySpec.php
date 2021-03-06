<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Sylius\Bundle\PaymentBundle\Doctrine\ORM;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\QueryBuilder;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PaymentMethodRepositorySpec extends ObjectBehavior
{
    public function let(EntityManager $em, ClassMetadata $classMetadata)
    {
        $this->beConstructedWith($em, $classMetadata);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Sylius\Bundle\TranslationBundle\Doctrine\ORM\TranslatableResourceRepository');
    }

    function it_is_a_repository()
    {
        $this->shouldHaveType('Sylius\Bundle\TranslationBundle\Doctrine\ORM\TranslatableResourceRepository');
        $this->shouldImplement('Sylius\Component\Payment\Repository\PaymentMethodRepositoryInterface');
    }

    function it_creates_query_builder_for_enable_status($em, QueryBuilder $builder)
    {
        $em->createQueryBuilder()->shouldBeCalled()->willReturn($builder);
        $builder->select('method')->shouldBeCalled()->willReturn($builder);
        $builder->addSelect('translation')->shouldBeCalled()->willReturn($builder);
        $builder->leftJoin('method.translations', 'translation')->shouldBeCalled()->willReturn($builder);
        $builder->from(Argument::any(), 'method', Argument::cetera())->shouldBeCalled()->willReturn($builder);
        $builder->where('method.enabled = true')->shouldBeCalled()->willReturn($builder);

        $this->getQueryBuilderForChoiceType([
            'disabled' => false,
        ])->shouldReturn($builder);
    }

    function it_creates_query_builder_for_all_status($em, QueryBuilder $builder)
    {
        $em->createQueryBuilder()->shouldBeCalled()->willReturn($builder);
        $builder->select('method')->shouldBeCalled()->willReturn($builder);
        $builder->addSelect('translation')->shouldBeCalled()->willReturn($builder);
        $builder->leftJoin('method.translations', 'translation')->shouldBeCalled()->willReturn($builder);
        $builder->from(Argument::any(), 'method', Argument::cetera())->shouldBeCalled()->willReturn($builder);

        $this->getQueryBuilderForChoiceType([
            'disabled' => true,
        ])->shouldReturn($builder);
    }
}
