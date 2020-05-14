<?php

/*
 * Copyright 2005 - 2020 Centreon (https://www.centreon.com/)
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * For more information : contact@centreon.com
 *
 */
declare(strict_types=1);

namespace Centreon\Application\Normalizer;

use Centreon\Domain\Monitoring\Resource;
use Centreon\Domain\Monitoring\Icon;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;

/**
 * Normalize icon url to build full url
 */
class IconUrlNormalizer implements ContextAwareNormalizerInterface
{
    private const IMG_DIR = '/img/media';

    /**
     * @inheritDoc
     */
    public function normalize($resource, $format = null, array $context = [])
    {
        // normalize resource icon
        if ($resource->getIcon() !== null) {
            $this->normalizeIcon($resource->getIcon());
        }

        // normalize parent resource icon
        if ($resource->getParent() !== null && $resource->getParent()->getIcon() !== null) {
            $this->normalizeIcon($resource->getParent()->getIcon());
        }

        return $resource;
    }

    /**
     * @inheritDoc
     */
    public function supportsNormalization($data, $format = null, array $context = [])
    {
        return $data instanceof Resource;
    }

    /**
     * Concat base url with icon path to get full url
     *
     * @param Icon $icon The icon to normalize
     * @return Icon
     */
    private function normalizeIcon(Icon $icon): Icon
    {
        if (isset($_SERVER['REQUEST_URI']) && preg_match('/^(.+)\/api\/.+/', $_SERVER['REQUEST_URI'], $matches)) {
            $icon->setUrl($matches[1] . self::IMG_DIR . '/' . $icon->getUrl());
        }

        return $icon;
    }
}