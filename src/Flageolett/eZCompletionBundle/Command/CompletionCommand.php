<?php
/**
 * @author Henning Kvinnesland <henning@keyteq.no>
 * @since 22.11.14
 */

namespace Flageolett\ezcompletionbundle\Command;

use eZ\Publish\API\Repository\Values\ContentType\ContentTypeGroup;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CompletionCommand extends ContainerAwareCommand
{
    const OPTION_NAME_COMPLETION_TYPE = 'type';

    protected function configure()
    {
        $this->setName('ezcode:completion');
        $this->addOption(self::OPTION_NAME_COMPLETION_TYPE, 't', InputOption::VALUE_REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $type = $input->getOption(self::OPTION_NAME_COMPLETION_TYPE);
        switch ($type) {
            case 'contentclass':
                $result = $this->contentClass();
            break;
            case 'contentclassid':
                $result = $this->contentClassId();
            break;
            case 'contentclassgroup':
                $result = $this->contentClassGroup();
                break;
            case 'contentclassgroupid':
                $result = $this->contentClassGroupId();
                break;
            default:
                $result = '';
        }

        $output->writeln($result);
    }

    protected function contentTypeService()
    {
        return $this->getContainer()->get('ezpublish.api.repository')->getContentTypeService();
    }

    protected function getContentClassGroups()
    {
        return $this->contentTypeService()->loadContentTypeGroups();
    }

    protected function contentClassGroup()
    {
        $groups = $this->getContentClassGroups();
        return array_map(function(ContentTypeGroup $group)
        {
            return $group->identifier;
        }, $groups);
    }

    protected function contentClass()
    {
        $groups = $this->getContentClassGroups();
        $classList = array();
        foreach ($groups as $group) {
            $contentTypes = $this->contentTypeService()->loadContentTypes($group);
            foreach ($contentTypes as $contentType) {
                $classList[] = $contentType->identifier;
            }
        }

        return $classList;
    }

    protected function contentClassGroupId()
    {
        $groups = $this->getContentClassGroups();
        return array_map(function (ContentTypeGroup $group) {
            return $group->id . ';' . $group->identifier;
        }, $groups);
    }

    protected function contentClassId()
    {
        $groups = $this->getContentClassGroups();
        $classList = array();
        foreach ($groups as $group) {
            $contentTypes = $this->contentTypeService()->loadContentTypes($group);
            foreach ($contentTypes as $contentType) {
                $classList[] = $contentType->id . ';' . $contentType->identifier;
            }
        }

        return $classList;
    }
}
