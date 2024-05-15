<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class SequenceGeneratorTest extends TestCase
{
    /**
     * @return void
     */
    public function testSequenceGenerator()
    {
        // PREFIX から始まる連番を生成
        /** @var SequenceGenerator $firstSeq */
        // SequenceGeneratorのgetInstanceメソッド呼び出し
        $firstSeq = SequenceGenerator::getInstance();

        $this->assertEquals('Tests\Unit\SequenceGenerator', get_class($firstSeq));
        //take：指定した件数データを取得する
        $this->assertEquals(
            ['PREFIX-1', 'PREFIX-2', 'PREFIX-3', 'PREFIX-4', 'PREFIX-5'],
            $firstSeq->take('PREFIX', 5)
        );
        $this->assertEquals(
            ['PREFIX-6', 'PREFIX-7', 'PREFIX-8'],
            $firstSeq->take('PREFIX', 3)
        );

        // CODE から始まる連番を生成
        $this->assertEquals(
            ['CODE-1', 'CODE-2', 'CODE-3', 'CODE-4', 'CODE-5']
            , SequenceGenerator::take('CODE', 5)
        );

        // 再度 PREFIX から始まる連番を生成
        /** @var SequenceGenerator $secondSeq */
        $secondSeq = SequenceGenerator::getInstance();
        $this->assertEquals(
            ['PREFIX-9', 'PREFIX-10', 'PREFIX-11', 'PREFIX-12'],
            $secondSeq->take('PREFIX', 4)
        );
        $this->assertEquals(
            ['PREFIX-13', 'PREFIX-14'],
            $firstSeq->take('PREFIX', 2)
        );
    }
}

class SequenceGenerator
{
    // ここに実装する

    private static $instance = null;
    private static $counters = []; //連番

    //必須要件：インスタンスを返す。
    public static function getInstance()
    {
        // インスタンスが無い(null)場合作成する。
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    //必須要件：引数で指定された Prefix を持つ連番を指定件数配列で返す。
    public static function take($prefix, $count)
    {
        // カウントの初期化。
        if (!isset(self::$counters[$prefix])) {
            self::$counters[$prefix] = 0;
        }

        // 引数で受け取ったカウント分の連想配列を生成する。
        $result = [];
        for ($i = 1; $i <= $count; $i++) {
            self::$counters[$prefix]++;
            $result[] = $prefix . '-' . self::$counters[$prefix];
        }
        // print_r($result);
        return $result;
    }
}

