<?php

declare(strict_types=1);

namespace Korus\Office\Service;

use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\Localization\Loc;
use DateTimeImmutable;
use Doctrine\ORM\Exception\NotSupported;
use Doctrine\ORM\NoResultException;
use Korus\Personalarea\Doctrine\Entity\MedicalPolicy;
use Korus\Personalarea\Doctrine\Entity\PolicyVersion;
use Korus\Personalarea\Helpers\DoctrineHelper;

class Insurance extends Service
{
    /**
     * @throws NotSupported
     * @throws LoaderException
     */
    public function getWidgetDetails(): string
    {
        Loader::requireModule('korus.personalarea');
        $now = new DateTimeImmutable();
        $entityManager = DoctrineHelper::getEntityManager();
        $queryBuilder = $entityManager->getRepository(MedicalPolicy::class)->createQueryBuilder('pol');
        $queryBuilder->leftJoin('pol.versions', 'version')
            ->where($queryBuilder->expr()->eq('pol.user_id', ':userId'))
            ->andWhere($queryBuilder->expr()->eq('pol.active', ':active'))
            ->andWhere($queryBuilder->expr()->eq('version.active', ':active'))
            ->andWhere(
                $queryBuilder->expr()->gte(':cur', 'pol.start'),
                $queryBuilder->expr()->lte(':cur', 'pol.deadline')
            )
            ->setParameters([
                'userId' => $this->user->getId(),
                'active' => true,
                'cur' => $now,
            ]);

        try {
            $policies = $queryBuilder->getQuery()->getResult();
            if (empty($policies)) {
                throw new NoResultException();
            }
        } catch (NoResultException) {
            return Loc::getMessage("INSURANCE_NOT_FOUND");
        }

        $resultHtml = '';
        /** @var MedicalPolicy $policy */
        foreach ($policies as $key => $policy) {
            if ($key !== 0) {
                $resultHtml .= '<br><br>';
            }

            $resultHtml .= sprintf('%s<br>', $policy->getNumber());
            $resultHtml .= sprintf(Loc::getMessage("INSURANCE_COMPANY").': %s<br>', $policy->getCompany()->getTitle());

            if (!empty($policy->getCompany()->getPhone())) {
                $resultHtml .= sprintf(Loc::getMessage("INSURANCE_COMPANY_PHONE").': %s<br>', $policy->getCompany()->getPhone());
            }

            $resultHtml .= sprintf(Loc::getMessage("INSURANCE_DEADLINE").': %s<br>', FormatDate("d.m.Y", $policy->getDeadline()->getTimestamp()));

            $programs = [];

            $versions = $policy->getVersions();
            if ($versions) {
                /** @var PolicyVersion $version */
                foreach ($versions as $version) {
                    if (!empty($version->getTitle())) {
                        $programs[] = $version->getTitle();
                    }
                }
            }

            if (!empty($programs)) {
                $resultHtml .= sprintf(Loc::getMessage("INSURANCE_PROGRAMS").': %s', implode(', ', $programs));
            }
        }

        return $resultHtml;
    }
}
