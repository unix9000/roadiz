<?php
/**
 * Copyright © 2015, Ambroise Maupate and Julien Blanchet
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is furnished
 * to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
 * OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
 * THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS
 * IN THE SOFTWARE.
 *
 * Except as contained in this notice, the name of the ROADIZ shall not
 * be used in advertising or otherwise to promote the sale, use or other dealings
 * in this Software without prior written authorization from Ambroise Maupate and Julien Blanchet.
 *
 * @file TagType.php
 * @author Ambroise Maupate
 */
namespace Themes\Rozier\Forms;

use RZ\Roadiz\CMS\Forms\Constraints\HexadecimalColor;
use RZ\Roadiz\CMS\Forms\Constraints\UniqueTagName;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * TagType.
 */
class TagType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('tagName', 'text', [
                'label' => 'tagName',
                'constraints' => [
                    new NotBlank(),
                    new UniqueTagName([
                        'entityManager' => $options['em'],
                        'currentValue' => $options['tagName'],
                    ]),
                ],
            ])
            ->add('color', 'text', [
                'label' => 'tag.color',
                'required' => false,
                'attr' => ['class' => 'colorpicker-input'],
                'constraints' => [
                    new HexadecimalColor(),
                ],
            ])
            ->add('visible', 'checkbox', [
                'label' => 'visible',
                'required' => false,
            ])
            ->add('locked', 'checkbox', [
                'label' => 'locked',
                'required' => false,
            ])
            ->add('childrenOrder', 'choice', [
                'label' => 'tag.childrenOrder',
                'choices_as_values' => true,
                'choices' => [
                    'position' => 'position',
                    'tagName' => 'tagName',
                    'createdAt' => 'createdAt',
                    'updatedAt' => 'updatedAt',
                ],
            ])
            ->add('childrenOrderDirection', 'choice', [
                'label' => 'tag.childrenOrderDirection',
                'choices_as_values' => true,
                'choices' => [
                     'ascendant' => 'ASC',
                     'descendant' => 'DESC',
                ],
            ]);
    }

    public function getName()
    {
        return 'tag';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => false,
            'tagName' => '',
            'data_class' => 'RZ\Roadiz\Core\Entities\Tag',
            'attr' => [
                'class' => 'uk-form tag-form',
            ],
        ]);

        $resolver->setRequired([
            'em',
        ]);

        $resolver->setAllowedTypes('em', 'Doctrine\Common\Persistence\ObjectManager');
        $resolver->setAllowedTypes('tagName', 'string');
    }
}
