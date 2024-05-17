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
    private static $instance = null;
    private static $counters;

    private static function init()
    {
        self::$counters = collect();
    }

    public static function getInstance()
    {
        // カウンタの初期化
        if (is_null(self::$counters)) {
            self::init();
        }
        // インスタンスが無い(null)場合作成する。
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // 引数で指定された Prefix を持つ連番を指定件数配列で返す
    public static function take($prefix, $count)
    {
        // カウンタの初期化。
        if (!self::$counters->has($prefix)) {
            self::$counters->put($prefix, 0);
        }
        // 引数で受け取ったカウント分の連番を生成する。
        $result = [];
        for ($i = 1; $i <= $count; $i++) {
            $result[] = $prefix . '-' . (self::$counters->get($prefix) + $i);
        }
        self::$counters->put($prefix, self::$counters->get($prefix) + $count); // カウンタ更新

        return $result;
    }
}
