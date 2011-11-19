<?php

/*
 * Copyright 2011 Johannes M. Schmitt <schmittjoh@gmail.com>
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
 */

namespace JMS\TranslationBundle\Tests\Translation\Dumper;

use JMS\TranslationBundle\Model\FileSource;

use JMS\TranslationBundle\Model\Message;
use JMS\TranslationBundle\Model\MessageDomainCatalogue;

abstract class BaseDumperTest extends \PHPUnit_Framework_TestCase
{
    public function testSimpleDump()
    {
        $domain = new MessageDomainCatalogue('messages', 'en');

        $message = new Message('foo');
        $domain->add($message);

        $this->assertEquals($this->getOutput('simple'), $this->dump($domain));
    }

    public function testDumpWithMetadata()
    {
        $domain = new MessageDomainCatalogue('messages', 'en');

        $message = new Message('foo');
        $message->setDesc('bar');
        $message->setMeaning('baz');
        $domain->add($message);

        $this->assertEquals($this->getOutput('with_metadata'), $this->dump($domain));
    }

    public function testDumpStructure()
    {
        $domain = new MessageDomainCatalogue('messages', 'en');

        $message = new Message('foo.bar.baz');
        $message->addSource(new FileSource('/a/b/c/foo/bar', 1, 2));
        $message->addSource(new FileSource('bar/baz', 1, 2));
        $domain->add($message);

        $this->assertEquals($this->getOutput('structure'), $this->dump($domain));
    }

    public function testDumpStructureWithMetadata()
    {
        $domain = new MessageDomainCatalogue('messages', 'en');

        $message = new Message('foo.bar.baz');
        $message->setDesc('Foo');
        $domain->add($message);

        $message = new Message('foo.bar.moo');
        $message->setMeaning('Bar');
        $domain->add($message);

        $message = new Message('foo.baz');
        $domain->add($message);

        $this->assertEquals($this->getOutput('structure_with_metadata'), $this->dump($domain));
    }

    abstract protected function getDumper();
    abstract protected function getOutput($key);

    private function dump(MessageDomainCatalogue $domain)
    {
        return $this->getDumper()->dump($domain);
    }
}