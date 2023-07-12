<?php

/**
 * @author Adam Zammit <adam.zammit@acspri.org.au>
 * @copyright 2023 ACSPRI
 * @license GPL version 3
 * @version 0.0.1
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */
class leastFilledFunctions extends PluginBase
{
    protected static $description = 'Add least filled to Expression Manager';
    protected static $name = 'leastFilledFunctions';

    /** @inheritdoc, this plugin doesn't have any public methods */
    public $allowedPublicMethods = array();

    public function init()
    {
        $this->subscribe('ExpressionManagerStart', 'newValidFunctions');
    }

    public function newValidFunctions()
    {
        Yii::setPathOfAlias(get_class($this), dirname(__FILE__));
        $newFunctions = array(
            'statLF' => array(
                '\leastFilledFunctions\leastFilledStatFunctions::statLF',
                null, // No javascript function : set as static function
                $this->gT("Return the least filled response"), // Description for admin
                'string statCountLF(QuestionCode.sgqa[, submitted = true][, self = true])', // Extra description
                'https://manual.limesurvey.org/StatFunctions', // Help url
                1, 2, 3 // Number of arguments : 2 , 3 or 4
            ),
        );
        $this->getEvent()->append('functions', $newFunctions);
    }
}
