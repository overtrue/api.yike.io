<?php

namespace App\Services\Filter;

/**
 * Class SensitiveFilter.
 *
 * @author v_haodouliu <haodouliu@gmail.com>
 */
class SensitiveFilter
{
    /**
     * 待检测语句长度.
     *
     * @var int
     */
    protected $contentLength = 0;

    /**
     * 铭感词库树.
     *
     * @var HashMap|null
     */
    protected $wordTree = null;

    public function __construct()
    {
        $this->buildTreeFromFile(\storage_path('SensitiveWords.txt'));
    }

    /**
     * 构建铭感词树【文件模式】.
     *
     * @param string $filePath
     *
     * @return $this
     */
    public function buildTreeFromFile($filePath = '')
    {
        if (!file_exists($filePath)) {
            \abort(404, '词库文件不存在');
        }
        // 词库树初始化
        $this->wordTree = new HashMap();

        foreach ($this->yieldToReadFile($filePath) as $word) {
            $this->buildWordToTree(trim($word));
        }

        return $this;
    }

    /**
     * 构建铭感词树【数组模式】.
     *
     * @param null $sensitiveWords
     *
     * @return $this
     */
    public function build($sensitiveWords = null)
    {
        if (empty($sensitiveWords)) {
            \abort(404, '词库不能为空');
        }

        $this->wordTree = new HashMap();

        foreach ($sensitiveWords as $word) {
            $this->buildWordToTree($word);
        }

        return $this;
    }

    /**
     * 检测文字中的敏感词.
     *
     * @param string $content   待检测内容
     * @param int    $matchType 匹配类型 [默认为最小匹配规则]
     * @param int    $count     需要获取的敏感词数量 [默认获取全部]
     *
     * @return array
     */
    public function getBadWords($content, $matchType = 1, $count = 0)
    {
        $this->contentLength = mb_strlen($content, 'utf-8');

        $badWords = [];

        for ($length = 0; $length < $this->contentLength; ++$length) {
            $matchFlag = 0;
            $flag = false;
            $tempMap = $this->wordTree;

            for ($i = $length; $i < $this->contentLength; ++$i) {
                $keyChar = mb_substr($content, $i, 1, 'utf-8');

                // 获取指定节点树
                $nowMap = $tempMap->get($keyChar);

                // 不存在节点树，直接返回
                if (empty($nowMap)) {
                    break;
                }

                // 存在，则判断是否为最后一个
                $tempMap = $nowMap;

                // 找到相应key，偏移量+1
                ++$matchFlag;

                // 如果为最后一个匹配规则,结束循环，返回匹配标识数
                if (false === $nowMap->get('ending')) {
                    continue;
                }

                $flag = true;

                // 最小规则，直接退出
                if (1 === $matchType) {
                    break;
                }
            }

            if (!$flag) {
                $matchFlag = 0;
            }

            // 找到相应key
            if ($matchFlag <= 0) {
                continue;
            }

            $badWords[] = mb_substr($content, $length, $matchFlag, 'utf-8');

            // 有返回数量限制
            if ($count > 0 && count($badWords) == $count) {
                return $badWords;
            }

            // 需匹配内容标志位往后移
            $length = $length + $matchFlag - 1;
        }

        return $badWords;
    }

    /**
     * 替换敏感字字符.
     *
     * @param        $content
     * @param string $replaceChar
     * @param int    $matchType
     *
     * @return mixed
     */
    public function replace($content, $replaceChar = '', $matchType = 1)
    {
        if (empty($content)) {
            \abort(404, '检测内容为空');
        }

        $badWords = $this->getBadWords($content, $matchType);

        // 未检测到敏感词，直接返回
        if (empty($badWords)) {
            return $content;
        }

        foreach ($badWords as $badWord) {
            $content = str_replace($badWord, $replaceChar, $content);
        }

        return $content;
    }

    /**
     * 被检测内容是否合法.
     *
     * @param $content
     *
     * @return bool
     */
    public function isLegal($content)
    {
        $this->contentLength = mb_strlen($content, 'utf-8');

        for ($length = 0; $length < $this->contentLength; ++$length) {
            $matchFlag = 0;
            $tempMap = $this->wordTree;

            for ($i = $length; $i < $this->contentLength; ++$i) {
                $keyChar = mb_substr($content, $i, 1, 'utf-8');

                // 获取指定节点树
                $nowMap = $tempMap->get($keyChar);

                // 不存在节点树，直接返回
                if (empty($nowMap)) {
                    break;
                }

                // 找到相应key，偏移量+1
                $tempMap = $nowMap;

                ++$matchFlag;

                // 如果为最后一个匹配规则,结束循环，返回匹配标识数
                if (false === $nowMap->get('ending')) {
                    continue;
                }

                return true;
            }

            // 找到相应key
            if ($matchFlag <= 0) {
                continue;
            }

            // 需匹配内容标志位往后移
            $length = $length + $matchFlag - 1;
        }

        return false;
    }

    /**
     * @param $filePath
     *
     * @return \Generator
     */
    protected function yieldToReadFile($filePath)
    {
        $fp = fopen($filePath, 'r');

        while (!feof($fp)) {
            yield fgets($fp);
        }

        fclose($fp);
    }

    /**
     * 将单个敏感词构建成树结构.
     *
     * @param string $word
     */
    protected function buildWordToTree($word = '')
    {
        if (empty($word)) {
            return;
        }

        $tree = $this->wordTree;

        $wordLength = mb_strlen($word, 'utf-8');

        for ($i = 0; $i < $wordLength; ++$i) {
            $keyChar = mb_substr($word, $i, 1, 'utf-8');

            // 获取子节点树结构
            $tempTree = $tree->get($keyChar);

            if ($tempTree) {
                $tree = $tempTree;
            } else {
                $newTree = new HashMap();
                $newTree->put('ending', false);

                $tree->put($keyChar, $newTree);
                $tree = $newTree;
            }

            // 到达最后一个节点
            if ($i == $wordLength - 1) {
                $tree->put('ending', true);
            }
        }
    }
}
