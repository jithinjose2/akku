<?php
/**
 * Created by PhpStorm.
 * User: jithin
 * Date: 2/1/17
 * Time: 12:01 PM
 */

namespace Akku\Repositories;

use Akku\Models\Action;
use Akku\Models\Rule;
use Akku\Models\Trigger;
use Akku\Models\Value;

class RuleRepository
{
    public function createRule($ruleData, $userId)
    {
        $ruleInfo['user_id'] = $userId;
        $ruleInfo['name'] = $ruleData['rule_name'];
        return Rule::create($ruleInfo);
    }

    public function addTrigger($ruleID, $ruleData)
    {
        $trigger = Trigger::where('rule_id', $ruleID)->first();

        if (count($trigger)) {
            $trigger->thing_id = $ruleData['action_thing'];
            $trigger->comparison_type = $ruleData['action_match'];
            $trigger->value = $ruleData['trigger_value'];
            $trigger->save();
        } else {
            $triggerData['rule_id'] = $ruleID;
            $triggerData['thing_id'] = $ruleData['action_thing'];
            $triggerData['comparison_type'] = $ruleData['action_match'];
            $triggerData['value'] = $ruleData['trigger_value'];
            $trigger = Trigger::create($triggerData);
        }
        return $trigger;
    }

    public function addAction($ruleID, $ruleData)
    {
        $action = Action::where('rule_id', $ruleID)->first();

        if ($action) {
            $action->thing_id = $ruleData['switch_selection'];
            $action->comparison_type = $ruleData['action_match'];
            $action->save();
        } else {
            $actionData['rule_id'] = $ruleID;
            $actionData['thing_id'] = $ruleData['switch_selection'];
            $actionData['value'] = $ruleData['turn_action'];
            $action = Action::create($actionData);
        }
        return $action;
    }

    public function getRulesList($userId)
    {
        $rulesList = Rule::where('user_id', $userId)
            ->with('trigger')
            ->with('action')
            ->get();
        return $rulesList;
    }

    public function deleteRule($ruleID)
    {
        Trigger::where('rule_id', $ruleID)->delete();
        Action::where('rule_id', $ruleID)->delete();
        Rule::where('id', $ruleID)->delete();
    }

    public function getHumidValues($humidId)
    {
        return array_map('floatval',Value::where('thing_id', $humidId)->orderBy('id', 'ASC')->get()->lists("value")->toArray());
    }

    public function getTempValues($tempID)
    {
        return array_map('floatval',Value::where('thing_id', $tempID)->orderBy('id', 'ASC')->get()->lists("value")->toArray());
    }

    public function getPowerValues($powerID)
    {
        return Value::where('thing_id', $powerID)->orderBy('id', 'DESC')->first();
    }
}