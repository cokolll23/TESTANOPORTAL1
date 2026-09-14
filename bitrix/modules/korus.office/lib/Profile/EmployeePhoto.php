<?php

namespace Korus\Office\Profile;

use Bitrix\Intranet\Component\UserProfile;
use Bitrix\Main\AccessDeniedException;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use CFile;
use Exception;

class EmployeePhoto
{
    const EMPLOYEE_AVATAR_SIZE = 212;

    /**
     * @var Employee
     */
    private Employee $employee;

    /**
     * @param Employee $employee
     */
    public function __construct(Employee $employee)
    {
        $this->employee = $employee;
    }

    /**
     * Возвращает фотографию пользователя
     *
     * @param int $size
     * @return string|null
     * @throws LoaderException
     */
    public function get(int $size = self::EMPLOYEE_AVATAR_SIZE): ?string
    {
        if (Loader::requireModule('intranet')) {
            $photoId = $this->employee->getField('PERSONAL_PHOTO');
            if ($photoId <= 0) {
                return $this->getDefaultSource();
            }

            return UserProfile::getUserPhoto($photoId, $size);
        } else {
            return null;
        }
    }

    /**
     * Возвращает картинку для фотографии пользователя по умолчанию
     *
     * @return int
     */
    private function getDefault(): int
    {
        $userGender = $this->employee->getField('PERSONAL_GENDER');
        $suffix = match ($userGender) {
            'M' => 'male',
            'F' => 'female',
            default => 'unknown',
        };

        return (int)Option::get('socialnetwork', 'default_user_picture_' . $suffix, false, SITE_ID);
    }

    /**
     * @param int $size
     * @return string
     */
    public function getDefaultSource(int $size = self::EMPLOYEE_AVATAR_SIZE): string
    {
        if($photoId = $this->getDefault()) {
            return UserProfile::getUserPhoto($photoId, $size);
        } else {
            return '';
        }

    }

    /**
     * @param array|string $photo
     * @return void
     * @throws AccessDeniedException|LoaderException
     * @throws Exception
     */
    public function update(array|string $photo): void
    {
        $this->checkPermission();
        $oldPhotoId = $this->employee->getField('PERSONAL_PHOTO');
        if($oldPhotoId) {
            CFile::Delete($oldPhotoId);
        }
        $newPhotoId = CFile::SaveFile($photo, 'main');
        $this->employee->setField('PERSONAL_PHOTO', $newPhotoId);
        $this->employee->save();
    }

    /**
     * @return void
     * @throws AccessDeniedException|LoaderException
     * @throws Exception
     */
    public function delete(): void
    {
        $this->checkPermission();
        $oldPhotoId = $this->employee->getField('PERSONAL_PHOTO');
        if($oldPhotoId) {
            CFile::Delete($oldPhotoId);
        }
        $this->employee->setField('PERSONAL_PHOTO', '');
        $this->employee->save();
    }

    /**
     * @return void
     * @throws AccessDeniedException
     * @throws LoaderException
     */
    private function checkPermission(): void
    {
        $permissions = $this->employee->getSocialNetworkPermissions();
        if (!$permissions['edit']) {
            throw new AccessDeniedException('Доступ запрещен.');
        }
    }
}
