<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 5/30/18
 * Time: 10:31 PM
 */

use App\Creator\Atoms\EPBonusMalus;
use App\Creator\Atoms\EPGear;
use App\Creator\Database;
use \App\Creator\EPCharacterCreator;

/**
 * A globally available helper function for accessing the creator
 * @return EPCharacterCreator|null
 */
function creator(): ?EPCharacterCreator
{
    return session('cc') ?? null;
}

/**
 * Get the location of the configuration file
 * TO Override this, set the 'EPCC_CONFIG_PATH' environment variable to the location of whatever config file you want.
 *
 * @return string
 */
function getConfigLocation(): string
{
    // Allow file path to be overridden by environment. (For some docker builds)
    if(isset($_ENV['EPCC_CONFIG_PATH'])) {
        return $_ENV['EPCC_CONFIG_PATH'];
    }
    return __DIR__ . '/../../config.ini';
}

/**
 * Returns a singleton of the Database class
 * @return Database
 */
function EpDatabase(): Database
{
    if(empty($_SESSION['db']))
    {
        session()->put('db', new Database());
    }
    return session('db');
}

/**
 * A version of `strtoupper` that replaces null with a space (' ')
 * @param null|string $string
 * @return string
 */
function toUpper(?string $string): string
{
    if($string == null)
        $string = " ";

    return strtoupper($string);
}

/******Filter functions******/

/**
 * @param array $bonusMalus
 * @return EPBonusMalus[]
 */
function getDescOnlyBM(array $bonusMalus): array
{
    $result = array();
    foreach($bonusMalus as $bm)
    {
        if($bm->bonusMalusType == EPBonusMalus::$DESCRIPTIVE_ONLY)
            array_push($result, $bm);
    }
    return $result;
}

/**
 * @param array $bonusMalus
 * @return EPBonusMalus[]
 */
function getMorphMemoBM(array $bonusMalus): array
{
    $result = array();
    foreach($bonusMalus as $bm)
    {
        if( $bm->bonusMalusType == EPBonusMalus::$DESCRIPTIVE_ONLY ||
            $bm->bonusMalusType == EPBonusMalus::$ON_ARMOR ||
            $bm->bonusMalusType == EPBonusMalus::$ON_ENERGY_ARMOR ||
            $bm->bonusMalusType == EPBonusMalus::$ON_KINETIC_ARMOR ||
            $bm->bonusMalusType == EPBonusMalus::$ON_ENERGY_WEAPON_DAMAGE ||
            $bm->bonusMalusType == EPBonusMalus::$ON_KINETIC_WEAPON_DAMAGE ||
            $bm->bonusMalusType == EPBonusMalus::$ON_MELEE_WEAPON_DAMAGE)
        {
            array_push($result, $bm);
        }
    }
    return $result;
}

/**
 * @param array $gears
 * @return EPGear[]
 */
function filterWeaponOnly(array $gears): array
{
    $result = array();
    foreach($gears as $g)
    {
        if( $g->gearType == EPGear::$WEAPON_MELEE_GEAR ||
            $g->gearType == EPGear::$WEAPON_ENERGY_GEAR ||
            $g->gearType == EPGear::$WEAPON_KINETIC_GEAR ||
            $g->gearType == EPGear::$WEAPON_AMMUNITION ||
            $g->gearType == EPGear::$WEAPON_SEEKER_GEAR ||
            $g->gearType == EPGear::$WEAPON_SPRAY_GEAR)
        {
            array_push($result, $g);
        }

        if ($g->isImplant()) {
            if ($g->degat != "0") {
                array_push($result, $g);
            }
        }
    }
    return $result;
}

/**
 * @param array $gears
 * @return EPGear[]
 */
function filterArmorOnly(array $gears): array
{
    $result = array();
    foreach($gears as $g)
    {
        if( $g->gearType == EPGear::$ARMOR_GEAR)
            array_push($result, $g);
    }
    return $result;
}

/**
 * @param array $gears
 * @return EPGear[]
 */
function filterImplantOnly(array $gears): array
{
    $result = array();
    foreach ($gears as $g) {
        if ($g->isImplant()) {
            array_push($result, $g);
        }
    }
    return $result;
}


/**
 * @param array $gears
 * @return EPGear[]
 */
function filterGeneralOnly(array $gears): array
{
    $result = array();
    foreach($gears as $g)
    {
        if( $g->gearType == EPGear::$STANDARD_GEAR ||
            $g->gearType == EPGear::$DRUG_GEAR ||
            $g->gearType == EPGear::$CHEMICALS_GEAR ||
            $g->gearType == EPGear::$POISON_GEAR ||
            $g->gearType == EPGear::$PET_GEAR ||
            $g->gearType == EPGear::$VEHICLES_GEAR ||
            $g->gearType == EPGear::$ROBOT_GEAR ||
            $g->gearType == EPGear::$FREE_GEAR )
        {
            array_push($result, $g);
        }
    }
    return $result;
}
