<?php

namespace Spider\Aspect;

use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Aspect;
use Hyperf\Di\Aop\ProceedingJoinPoint;
use Spider\Annotation\Transaction;
use Spider\Exception\TransactionException;

#[Aspect]
class TransactionAspect
{
    public array $annotations = [
        Transaction::class
    ];

    public function process(ProceedingJoinPoint $proceedingJoinPoint)
    {
        if (isset($proceedingJoinPoint->getAnnotationMetadata()->method[Transaction::class])) {
            $transaction = $proceedingJoinPoint->getAnnotationMetadata()->method[Transaction::class];
        }

        $connection = $transaction->connection;

        Db::connection($connection)->beginTransaction();
        try {
            $number = 0;
            $retry  = intval($transaction->retry);
            do {
                $result = $proceedingJoinPoint->process();
                if (! is_null($result)) {
                    break;
                }
                ++$number;
            } while ($number < $retry);

            Db::connection($connection)->commit();
        } catch (\Throwable $e) {
            Db::connection($connection)->rollBack();
            throw new TransactionException($e->getMessage());
        }

        return $result;
    }
}