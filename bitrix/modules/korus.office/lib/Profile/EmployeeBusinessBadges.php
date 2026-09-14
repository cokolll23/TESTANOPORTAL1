<?php

namespace Korus\Office\Profile;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\DI\ServiceLocator;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\SystemException;
use CFile;
use Psr\Container\NotFoundExceptionInterface;

class EmployeeBusinessBadges
{
    /**
     * @var Employee
     */
    private Employee $employee;

    /**
     * @var mixed
     */
    private mixed $em;

    /**
     * @var
     */
    private $businessBadges;

    /**
     * @var
     */
    private $usersBusinessBadges;

    /**
     * @param Employee $employee
     * @throws SystemException|NotFoundExceptionInterface
     */
    public function __construct(Employee $employee)
    {
        $this->em = ServiceLocator::getInstance()->get('korus.main.entity.manager');

        $this->usersBusinessBadges = $this->em->Highload('UsersBusinessBadges')->getHighloadEntity();
        $this->businessBadges = $this->em->Highload('BusinessBadges')->getHighloadEntity();

        $this->employee = $employee;
    }

    /**
     * @return array
     * @throws SystemException
     * @throws ArgumentException
     * @throws ObjectPropertyException
     */
    public function get(): array
    {

        $queryBuilder = $this->em->createQueryBuilder($this->usersBusinessBadges);
        $badges = $queryBuilder
            ->setSelect([
                'ID',
                'NAME' => 'SIGN_REF.UF_NAME',
                'DESCRIPTION' => 'SIGN_REF.UF_DESCRIPTION',
                'IMAGE' => 'SIGN_REF.UF_IMG',
                'COLOR' => 'SIGN_REF.UF_COLOR',
                'DATE' => 'UF_DATE',
            ])
            ->where('UF_USER', $this->employee->getId())
            ->setOrder(['UF_DATE' => 'DESC'])
            ->registerRuntimeField(new Reference(
                'SIGN_REF',
                $this->businessBadges->getDataClass(),
                Join::on('this.UF_BUSINESS_SIGNS', 'ref.ID')
            ))
            ->getQuery()
            ->fetchAll();

        $result = [];

        foreach ($badges as $badge) {
            $result[] = [
                'id' => $badge['ID'],
                'name' => $badge['NAME'],
                'description' => $badge['DESCRIPTION'],
                'image' => CFile::GetPath($badge['IMAGE']),
                'date' => $badge['DATE'],
                'color' => $badge['COLOR']
            ];
        }

        return $result;
    }
}
