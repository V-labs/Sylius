<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Sylius\Component\Taxonomy\Model;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Sylius\Component\Taxonomy\Model\TaxonomyInterface;
use Sylius\Component\Taxonomy\Model\TaxonomyTranslation;

/**
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 * @author Gonzalo Vilaseca <gvilaseca@reiss.co.uk>
 */
class TaxonomySpec extends ObjectBehavior
{
    public function let()
    {
        $this->setCurrentLocale('en_US');
        $this->setFallbackLocale('en_US');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Sylius\Component\Taxonomy\Model\Taxonomy');
    }

    function it_implements_Sylius_taxonomy_interface()
    {
        $this->shouldImplement(TaxonomyInterface::class);
    }

    function it_has_no_id_by_default()
    {
        $this->getId()->shouldReturn(null);
    }

    function it_calls_translation_to_string(TaxonomyTranslation $translation, TaxonInterface $root)
    {
        $this->setRoot($root);

        $translation->getLocale()->willReturn('en_US');
        $translation->setTranslatable($this)->shouldBeCalled();
        $translation->getName()->shouldBeCalled();

        $this->addTranslation($translation);
        $translation->__toString()->shouldBeCalled();

        $this->__toString();
    }

    function it_has_no_root_by_default()
    {
        $this->getRoot()->shouldReturn(null);
    }

    function it_allows_setting_the_root_taxon(TaxonInterface $taxon)
    {
        $this->setRoot($taxon);
        $this->getRoot()->shouldReturn($taxon);
    }

    function it_is_unnamed_by_default(TaxonInterface $root)
    {
        $this->setRoot($root);

        $this->getName()->shouldReturn(null);
    }

    function it_delegates_current_and_fallback_locale_to_root_taxon(TaxonInterface $taxon)
    {
        $taxon->setCurrentLocale('en_US')->shouldBeCalled();
        $taxon->setFallbackLocale('en_US')->shouldBeCalled();
        $taxon->setTaxonomy($this)->shouldBeCalled();

        $this->setRoot($taxon);

        $this->setCurrentLocale('en_US');
        $this->setFallbackLocale('en_US');
    }

    function its_name_is_mutable(TaxonInterface $taxon)
    {
        $taxon->setName(null)->shouldBeCalled();
        $taxon->setName('Brand')->shouldBeCalled();
        $taxon->setTaxonomy($this)->shouldBeCalled();
        $taxon->setCurrentLocale('en_US')->shouldBeCalled();
        $taxon->setFallbackLocale('en_US')->shouldBeCalled();

        $this->setRoot($taxon);

        $this->setName('Brand');
        $this->getName()->shouldReturn('Brand');
    }

    function it_also_sets_name_on_the_root_taxon(TaxonInterface $taxon)
    {
        $taxon->setName(null)->shouldBeCalled();
        $taxon->setName('Category')->shouldBeCalled();
        $taxon->setTaxonomy($this)->shouldBeCalled();

        $taxon->setCurrentLocale('en_US')->shouldBeCalled();
        $taxon->setFallbackLocale('en_US')->shouldBeCalled();
        $taxon->setName('Category')->shouldBeCalled();
        $taxon->setTaxonomy($this)->shouldBeCalled();

        $this->setRoot($taxon);

        $this->setName('Category');
    }

    function it_sets_code_for_root_taxon(TaxonInterface $taxon)
    {
        $this->setRoot($taxon);

        $taxon->setCode('RTX2')->shouldBeCalled();
        $taxon->getCode()->shouldBeCalled()->willReturn('RTX2');

        $this->setCode('RTX2');
        $this->getCode()->shouldReturn('RTX2');
    }

    function it_delegates_the_hasTaxon_method_to_root_taxon(TaxonInterface $root, TaxonInterface $taxon)
    {
        $this->setRoot($root);

        $root->hasChild($taxon)->willReturn(true);
        $this->hasTaxon($taxon)->shouldReturn(true);

        $root->hasChild($taxon)->willReturn(false);
        $this->hasTaxon($taxon)->shouldReturn(false);
    }

    function it_delegates_addTaxon_method_to_root_taxon(TaxonInterface $root, TaxonInterface $taxon)
    {
        $this->setRoot($root);

        $root->addChild($taxon)->shouldBeCalled();
        $this->addTaxon($taxon);
    }

    function it_delegates_removeTaxon_method_to_root_taxon(TaxonInterface $root, TaxonInterface $taxon)
    {
        $this->setRoot($root);

        $root->removeChild($taxon)->shouldBeCalled();
        $this->removeTaxon($taxon);
    }

    function it_also_sets_translation_on_root_taxon(TaxonInterface $taxon, TaxonomyTranslation $translation)
    {
        $translation->getName()->willReturn('New');
        $translation->getLocale()->shouldBeCalled();
        $translation->setTranslatable($this)->shouldBeCalled();

        $taxon->setName('New')->shouldBeCalled();
        $taxon->setTaxonomy($this)->shouldBeCalled();
        $taxon->setCurrentLocale(Argument::any())->shouldBeCalled();
        $taxon->setFallbackLocale(Argument::any())->shouldBeCalled();

        $this->setRoot($taxon);

        $this->addTranslation($translation);
    }
}
