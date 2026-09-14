<?php

declare(strict_types=1);

namespace Korus\Office\Profile;

use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\ORM\Data\Result;
use Bitrix\Main\SystemException;
use CSmile;
use CSmileSet;
use Exception;

class EmployeeEmoji
{
    /**
     * @var Employee
     */
    private Employee $employee;

    /**
     * @var int
     */
    private int $smileKitId;

    /**
     * @var string|int|array
     */
    private string|int|array $emojiId;

    /**
     * @param Employee $employee
     */
    public function __construct(Employee $employee)
    {
        $this->employee = $employee;
    }

    /**
     * @return Result
     * @throws SystemException
     */
    public function getList(): Result
    {
        try {
            $result = new Result();

            $this->getSmileKitId();

            /**
             * @var array $query
             */
            $query = CSmile::getList([
                'SELECT' => ['NAME', 'IMAGE', 'TYPE', 'SET_ID'],
                'FILTER' => [
                    'SET_ID' => [$this->smileKitId, CSmileSet::getByStringId('smiles')['ID']],
                ],
            ]);

            $query = $this->prepareResult($query);

            $result->setData($query);

            return $result;
        } catch (Exception $e) {
            throw new SystemException($e->getMessage());
        }
    }

    /**
     * @return Result
     * @throws SystemException
     */
    public function get(): Result
    {
        try {
            $result = new Result();

            $emojiId = $this->employee->getField('UF_EMOJI');

            if (!$emojiId) {
                return $result;
            }

            $this->emojiId = $emojiId;

            $this->getSmileKitId();

            $emoji = $this->getEmojiById();

            $result->setData($this->prepareResult($emoji));

            return $result;
        } catch (Exception $e) {
            throw new SystemException($e->getMessage());
        }
    }

    /**
     * @param int $emojiId
     * @return bool
     * @throws SystemException
     */
    public function put(int $emojiId): bool
    {
        try {
            $this->emojiId = $emojiId;

            if ($this->emojiId) {
                $this->getEmojiById();
            }

            $this->employee->setField('UF_EMOJI', $this->emojiId ?? '');

            return $this->employee->save();
        } catch (Exception $e) {
            throw new SystemException($e->getMessage());
        }
    }

    /**
     * @throws ArgumentNullException
     */
    public function getSmileKitId(): void
    {
        $query = CSmileSet::getByStringId(
            'status_emoji',
            CSmileSet::TYPE_SET,
            'ru'
        );

        if (!$query['ID']) {
            throw new ArgumentNullException('Не найден сет эмодзи для профиля сотрудника');
        }

        $this->smileKitId = (int) $query['ID'];
    }

    /**
     * @param array $query
     * @return array
     */
    private function prepareResult(array $query): array
    {
        $result = [];

        foreach ($query as $emoji) {
            if ($emoji['NAME'] === null) {
                continue;
            }

            $result[] = $emoji;
        }

        foreach ($result as &$emoji) {
            $emoji['IMAGE'] = $emoji['TYPE'] === 'S' ? CSmile::PATH_TO_SMILE . "{$emoji['SET_ID']}/{$emoji['IMAGE']}"
                : CSmile::PATH_TO_ICON . "{$emoji['SET_ID']}/{$emoji['IMAGE']}";
            unset($emoji['TYPE']);
        }

        return $result;
    }

    /**
     * @return array
     * @throws ArgumentNullException
     */
    private function getEmojiById(): array
    {
        /**
         * @var array $query
         */
        $query = CSmile::getList([
            'SELECT' => ['NAME', 'IMAGE', 'TYPE', 'SET_ID'],
            'FILTER' => [
                'ID' => $this->emojiId,
            ]
        ]);

        if (!$query) {
            throw new ArgumentNullException('Эмодзи с таким ИД не найден');
        }

        return $query;
    }
}
