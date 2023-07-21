<?php
/**
 * This class is a part of the package  and the package
 * is a part of the project expression.
 *
 * Integrated ICT Pvt. Ltd. Jwagal, Lalitpur, Nepal.
 * https://www.integratedict.com.np
 * https://www.semantro.com
 *
 * Created by Santa on 2023-06-18.
 * Email: sbasnet81[at]gmail[dot]com
 * Github: https://github.com/santabasnet
 */

enum Actions
{
    case CREATE;
    case UPDATE;
    case SEARCH;
    case DELETE;
}

function action_name_of($action)
{
    switch ($action) {
        case Actions::CREATE:
            return "create";
        case Actions::UPDATE:
            return "update";
        case Actions::SEARCH:
            return "search";
        case Actions::DELETE:
            return "delete";
    }
}