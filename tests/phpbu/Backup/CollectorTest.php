<?php
namespace phpbu\Backup;

/**
 * Collector test
 *
 * @package    phpbu
 * @subpackage tests
 * @author     Sebastian Feldmann <sebastian@phpbu.de>
 * @copyright  2014 Sebastian Feldmann <sebastian@phpbu.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       http://www.phpbu.de/
 * @since      Class available since Release 1.0.0
 */
class CollectorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test the Backup collector with no dynamic directory
     * Files not matching foo-%d.txt should be ignored.
     */
    public function testMatchFiles()
    {
        $path     = $this->getTestDataDir() . '/collector/static-dir';
        $filename = 'foo-%d.txt';
        $target   = new Target($path   , $filename, strtotime('2014-12-01 04:30:57'));
        $files    = Collector::getBackupFiles($target);

        $this->assertEquals(4, count($files), '4 files should be found');
    }

    /**
     * Test the Backup collector with one dynamic directory
     */
    public function testSingleDynamicDirectory()
    {
        $path     = $this->getTestDataDir() . '/collector/dynamic-dir/single/%m';
        $filename = '%d.txt';
        $target   = new Target($path, $filename, strtotime('2014-12-01 04:30:57'));
        $files    = Collector::getBackupFiles($target);

        $this->assertEquals(4, count($files), '4 files should be found');
    }

    /**
     * Test the Backup collector with one dynamic directory ignoring current backup
     */
    public function testSingleDynamicDirectorySkipCurrent()
    {
        $path     = $this->getTestDataDir() . '/collector/dynamic-dir/single/%Y%m';
        $filename = '%d.txt';
        $target   = new Target($path, $filename, strtotime('2014-03-17 04:30:57'));
        $files    = Collector::getBackupFiles($target);

        $this->assertEquals(3, count($files), '3 files should be found');
    }

    /**
     * Test the Backup collector with multi dynamic directories
     */
    public function testMultipleDynamicDirectories()
    {
        $path     = $this->getTestDataDir() . '/collector/dynamic-dir/multi/%m/%d';
        $filename = '%H.txt';
        $target   = new Target($path, $filename, strtotime('2014-12-01 04:30:57'));
        $files    = Collector::getBackupFiles($target);

        $this->assertEquals(8, count($files), '8 files should be found');
    }

    /**
     * Test the Backup collector with multi dynamic directories and ignoring current backup
     */
    public function testMultipleDynamicDirectoriesSkipCurrent()
    {
        $path     = $this->getTestDataDir() . '/collector/dynamic-dir/multi/%m/%d';
        $filename = '%H.txt';
        $target   = new Target($path, $filename, strtotime('2014-02-02 22:30:57'));
        $files    = Collector::getBackupFiles($target);

        $this->assertEquals(7, count($files), '7 files should be found');
    }

    /**
     * Return testdata directory
     *
     * @return string
     */
    protected function getTestDataDir()
    {
        return realpath(__DIR__ . '/../../_files');
    }
}