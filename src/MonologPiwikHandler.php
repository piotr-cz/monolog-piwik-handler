<?php
/**
 * Piwik handler for monolog
 *
 * @see http://developer.piwik.org/api-reference/PHP-Piwik-Tracker#dotrackevent
 * @see https://github.com/Seldaek/monolog/blob/master/doc/04-extending.md
 * @see \Monolog\Handler\IFTTTHandler
 */

namespace PiotrCz\MonologPiwikHandler;

use PiwikTracker;

use Monolog\Logger;
use Monolog\Handler\AbstractProcessingHandler;

/**
 * Sends notifications trough Piwik API
 */
class PiwikHandler extends AbstractProcessingHandler
{
    /**
     * @var PiwikTracker
     */
    protected $piwikTracker;

    /**
     * @var string
     */
    protected $eventsCategory;

    /**
     * Constructor.
     *
     * @param PiwikTracker $piwikTracker - Pwik tracker client
     * @param string $category - Event category
     * @param integer $level - The minimum logging level at which this handler will be triggered
     * @param boolean $bubble - Whether the messages that are handled can bubble up the stack or not
     */
    public function __construct(PiwikTracker $piwikTracker, $category = 'Errors', $level = Logger::ERROR, $bubble = true)
    {
        $this->piwikTracker = $piwikTracker;
        $this->eventsCategory = $category;

        parent::__construct($level, $bubble);
    }

    /**
     * Writes the record down to the log of the implementing handler
     *
     * @param array $record {
     *   @var string $message
     *   @var mixed $context
     *   @var integer $level
     *   @var string $level_name
     *   @var string $channel
     *   @var DateTime $datetime
     *   @var array $extra
     * }
     */
    protected function write(array $record)
    {
        /** @var string Response Content */
        $this->piwikTracker->doTrackEvent(
            // Category
            $this->eventsCategory,
            // Action (level name)
            $record['channel'] . '.' . $record['level_name'],
            // Name (exception name)
            $record['formatted'],
            // Value (log level importance)
            $record['level']
        );
    }
}
