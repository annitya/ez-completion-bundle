<?php
/**
 * User:    Henning Kvinnesland
 * E-mail:  henning@byte.no
 * Date:    23.02.2016
 * Time:    19.49
 */

namespace Flageolett\eZCompletionBundle\Examples;

use eZ\Publish\API\Repository\ContentService;
use eZ\Publish\API\Repository\ContentTypeService;
use eZ\Publish\API\Repository\FieldTypeService;
use eZ\Publish\API\Repository\LanguageService;
use eZ\Publish\API\Repository\ObjectStateService;
use eZ\Publish\API\Repository\Repository;
use eZ\Publish\API\Repository\RoleService;
use eZ\Publish\API\Repository\SectionService;
use eZ\Publish\API\Repository\URLAliasService;
use eZ\Publish\Core\MVC\Symfony\Controller\Controller;

/**
 * Examples for completions available from repository.
 * | indicates cursor position upon completion-request.
 */
class Examples extends Controller
{
    public function configResolver()
    {
        // Scope-dependent parameters.
        $this->getConfigResolver()->getParameter('|');
        // Result
        $this->getConfigResolver()->getParameter('content.tree_root.location_id');
    }

    public function repositoryCompletions(Repository $repository)
    {
        // module
        $repository->hasAccess('|');
        // function
        $repository->hasAccess('content', '|');
        //Result
        $repository->hasAccess('content', 'edit');

        // module
        $repository->canUser('|');
        // function
        $repository->canUser('collaboration', '|');
        // Result
        $repository->canUser('collaboration', 'view');
    }

    public function contentService(ContentService $contentService, $content)
    {
        $contentService->newContentCreateStruct($content, '|');
        // Result
        $contentService->newContentCreateStruct($content, 'eng-GB');
    }

    public function contentLanguageService(LanguageService $languageService)
    {
        // loadLanguage
        $languageService->loadLanguage('|');
        // Result
        $languageService->loadLanguage('eng-GB');

        // loadLanguageById
        $languageService->loadLanguageById('|');
        // Result
        $languageService->loadLanguageById(4);
    }

    public function contentTypeService(ContentTypeService $contentTypeService)
    {
        // loadContentType
        $contentTypeService->loadContentType('|');
        // Result
        $contentTypeService->loadContentType(4);

        // loadContentTypeByIdentifier
        $contentTypeService->loadContentTypeByIdentifier('|');
        // Result
        $contentTypeService->loadContentTypeByIdentifier('article');

        // loadContentTypeByRemoteId
        $contentTypeService->loadContentTypeByRemoteId('|');
        // Result
        $contentTypeService->loadContentTypeByRemoteId('77f3ede996a3a39c7159cc69189c5307');

        // LoadContentTypeDraft
        $contentTypeService->loadContentTypeDraft('|');
        // Result
        $contentTypeService->loadContentTypeDraft(3);

        // loadContentTypeGroup
        $contentTypeService->loadContentTypeGroup('|');
        // Result
        $contentTypeService->loadContentTypeGroup(2);

        // loadContentTypeGroupByIdentifier
        $contentTypeService->loadContentTypeGroupByIdentifier('|');
        $contentTypeService->loadContentTypeGroupByIdentifier('Content');
    }

    public function fieldTypeService(FieldTypeService $fieldTypeService)
    {
        // getFieldType
        $fieldTypeService->getFieldType('|');
        // Result
        $fieldTypeService->getFieldType('ezauthor');

        // hasFieldType
        $fieldTypeService->hasFieldType('|');
        // Result
        $fieldTypeService->hasFieldType('ezdate');
    }

    public function urlAliasService(URLAliasService $urlAliasService)
    {
        // language-completion
        $urlAliasService->createGlobalUrlAlias('', '', '|');
        // Result
        $urlAliasService->createGlobalUrlAlias('', '', 'eng-GB');
    }

    public function objectStateService(ObjectStateService $objectStateService)
    {
        // loadObjectState
        $objectStateService->loadObjectState('|');
        // Result
        $objectStateService->loadObjectState(1);

        // loadObjectStateGroup
        $objectStateService->loadObjectStateGroup('|');
        // Result
        $objectStateService->loadObjectStateGroup(2);
    }

    public function roleService(RoleService $roleService)
    {
        // getLimitationType
        $roleService->getLimitationType('|');
        // Result
        $roleService->getLimitationType('ParentClass');

        // loadRole
        $roleService->loadRole('|');
        // Result
        $roleService->loadRole(3);

        // loadRoleByIdentifier
        $roleService->loadRoleByIdentifier('|');
        // Result
        $roleService->loadRoleByIdentifier('Anonymous');

        // module
        $roleService->getLimitationTypesByModuleFunction('|');
        // function
        $roleService->getLimitationTypesByModuleFunction('content', '|');
        // Result
        $roleService->getLimitationTypesByModuleFunction('content', 'bookmark');
    }

    public function sectionService(SectionService $sectionService)
    {
        // loadSection
        $sectionService->loadSection('|');
        // Result
        $sectionService->loadSection(5);

        // loadSectionByIdentifier
        $sectionService->loadSectionByIdentifier('|');
        // Result
        $sectionService->loadSectionByIdentifier('setup');
    }
}