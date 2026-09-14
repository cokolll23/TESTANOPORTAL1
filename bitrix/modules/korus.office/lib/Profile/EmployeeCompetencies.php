<?php

namespace Korus\Office\Profile;

use Bitrix\Main\AccessDeniedException;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\DI\ServiceLocator;
use Bitrix\Main\Error;
use Bitrix\Main\InvalidOperationException;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\ORM\Data\AddResult;
use Bitrix\Main\ORM\Data\DeleteResult;
use Bitrix\Main\ORM\Data\Result;
use Bitrix\Main\SystemException;
use Exception;
use Korus\Personalarea\Orm\CompetenceTable;
use Psr\Container\NotFoundExceptionInterface;

/**
 *
 */
class EmployeeCompetencies
{
    /**
     * @var Employee
     */
    private Employee $employee;

    /**
     * @param Employee $employee
     * @throws LoaderException
     */
    public function __construct(Employee $employee)
    {
        Loader::requireModule('korus.personalarea');

        $this->employee = $employee;
    }

    /**
     * @return Result
     * @throws NotFoundExceptionInterface
     */
    public function list(): Result
    {
        $result = new Result();

        try {
            $competences = [];

            $ids = $this->getEmployeeCompetence();
            if (!empty($ids)) {

                $em = ServiceLocator::getInstance()->get('korus.main.entity.manager');

                $queryBuilder = $em->createQueryBuilder($em->Table(CompetenceTable::class)->createEntity());
                $competences = $queryBuilder
                    ->setSelect(['ID', 'TITLE'])
                    ->whereIn('ID', $ids)
                    ->getQuery()
                    ->fetchAll();
            }

            $result->setData($competences);
        } catch (Exception $e) {
            $result->addError(new Error($e->getMessage()));
        }

        return $result;
    }

    /**
     * @param string $tag
     * @return AddResult
     * @throws NotFoundExceptionInterface
     */
    public function add(string $tag): AddResult
    {
        $result = new AddResult();
        $tag = trim($tag, " \n\r\t\v\0,");
        try {
            $this->checkContext($tag);

            $resultData = [];

            $id = static::getCompetenceId($tag);
            if (!$id) {

                $em = ServiceLocator::getInstance()->get('korus.main.entity.manager');
                $res = $em->Table(CompetenceTable::class)->createEntity()->getDataClass()::add(['TITLE' => $tag]);
                if (!$res->isSuccess()) {
                    throw new InvalidOperationException(sprintf('Не удалось добавить компетенцию: %s', implode(PHP_EOL, $res->getErrorMessages())));
                }

                $id = $res->getId();
            }

            $ids = $this->getEmployeeCompetence();
            $ids[] = $id;

            $this->saveEmployeeCompetence($ids);

            $resultData[] = [
                'ID' => $id,
                'TITLE' => $tag
            ];

            $result->setData($resultData);
        } catch (Exception $e) {
            $result->addError(new Error($e->getMessage()));
        }

        return $result;
    }

    /**
     * @param string $tag
     * @return DeleteResult
     * @throws NotFoundExceptionInterface
     */
    public function delete(string $tag): DeleteResult
    {
        $result = new DeleteResult();

        try {
            $this->checkContext($tag);

            $tagId = static::getCompetenceId($tag);
            $ids = $this->getEmployeeCompetence();

            $index = array_search($tagId, $ids);
            if ($index !== false) {
                unset($ids[$index]);
            }

            $this->saveEmployeeCompetence($ids);
        } catch (Exception $e) {
            $result->addError(new Error($e->getMessage()));
        }

        return $result;
    }

    /**
     * @return array
     */
    private function getEmployeeCompetence(): array
    {
        return $this->employee->getField('UF_COMPETENCE') ?: [];
    }

    /**
     * @param array $ids
     * @return void
     * @throws Exception
     */
    private function saveEmployeeCompetence(array $ids): void
    {
        $this->employee->setField('UF_COMPETENCE',array_unique($ids));
        $this->employee->save();
    }

    /**
     * @param string $tag
     * @return int
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException|NotFoundExceptionInterface
     */
    private static function getCompetenceId(string $tag): int
    {
        $em = ServiceLocator::getInstance()->get('korus.main.entity.manager');

        $queryBuilder = $em->createQueryBuilder($em->Table(CompetenceTable::class)->createEntity());
        $competence = $queryBuilder
            ->setSelect(['ID'])
            ->whereIn('TITLE', $tag)
            ->getQuery()
            ->fetch();

        return (int)$competence['ID'];
    }

    /**
     * @param string $tag
     * @return void
     * @throws AccessDeniedException
     * @throws ArgumentNullException|LoaderException
     */
    private function checkContext(string $tag): void
    {
        if (empty($tag)) {
            throw new ArgumentNullException('tag');
        }

        if (!$this->employee->canEdit()) {
            throw new AccessDeniedException();
        }
    }

    public function search(string $tag = ''): Result
    {
        $result = new Result();
        try {
            $query = CompetenceTable::query()
                ->addOrder('TITLE')
                ->addSelect('ID')
                ->addSelect('TITLE');
            if (!empty($tag)) {
                $query->whereLike('TITLE', trim($tag) . '%');
            }
            $ids = $this->getEmployeeCompetence();
            if (!empty($ids)) {
                $query->whereNotIn('ID', $ids);
            }
            $result->setData($query->fetchAll());
        } catch (\Exception $e) {
            $result->addError(new Error($e->getMessage()));
        }
        return $result;
    }
}
