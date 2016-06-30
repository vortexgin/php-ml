<?php

declare (strict_types = 1);

namespace Phpml\FeatureExtraction;

use Phpml\Transformer;

class TfIdfTransformer implements Transformer
{
    /**
     * @var array
     */
    private $idf;

    /**
     * @param array $samples
     */
    public function __construct(array $samples = null)
    {
        if ($samples) {
            $this->fit($samples);
        }
    }

    /**
     * @param array $samples
     */
    public function fit(array $samples)
    {
        $this->countTokensFrequency($samples);

        $count = count($samples);
        foreach ($this->idf as &$value) {
            $value = log($count / $value, 10);
        }
    }

    /**
     * @param array $samples
     */
    public function transform(array &$samples)
    {
        foreach ($samples as &$sample) {
            foreach ($sample as $index => &$feature) {
                $feature = $feature * $this->idf[$index];
            }
        }
    }

    /**
     * @param array $samples
     *
     * @return array
     */
    private function countTokensFrequency(array $samples)
    {
        $this->idf = array_fill_keys(array_keys($samples[0]), 0);

        foreach ($samples as $sample) {
            foreach ($sample as $index => $count) {
                if ($count > 0) {
                    ++$this->idf[$index];
                }
            }
        }
    }
}