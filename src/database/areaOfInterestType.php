<?php
/**
    According to notes/2015-07-10.md we expect several types of AOIs,
    and the below class shall be used to describe them.
    The below class follows the simple enum pattern described in [1].
    An alternative would be to extend SplEnum [2].
    [1]: https://stackoverflow.com/a/254543/448591
    [2]: https://secure.php.net/manual/en/class.splenum.php
*/
abstract class AreaOfInterestType {
    //Possible types for AOIs below:
    const MainText           = 0;
    const MarginalText       = 1;
    const Image              = 2;
    const MathematicalFigure = 3;
    const Other              = 4;
    const Initials           = 5;
    const Title              = 6;
    /**
        @return [Type => Description]
        Returns a map from valid value types to short descriptions for them.
    */
    public static function types(){
        return array(
            AreaOfInterestType::MainText           => 'main text'
        ,   AreaOfInterestType::MarginalText       => 'marginal text'
        ,   AreaOfInterestType::Image              => 'image'
        ,   AreaOfInterestType::MathematicalFigure => 'mathematical figure'
        ,   AreaOfInterestType::Other              => 'other'
        ,   AreaOfInterestType::Initials           => 'initials'
        ,   AreaOfInterestType::Title              => 'title'
        );
    }
    /**
        @param $t Type
        Predicate to decide if a given Type $v is a valid value for an AreaOfInterestType.
    */
    public static function validType($t){
        return array_has_key($t, $this->types());
    }
    /**
        @param $t Type
        @return $has Boolean
        Predicate that returns true iff the given Value
        is expected to carry some text with it.
    */
    public static function hasText($t){
        switch($t){
            case AreaOfInterestType::Other:
                return true;
            default:
                return false;
        }
    }
    /**
        @return array($typeEnum => array('label' => String, 'hasText' => Bool))
        This method shall be used to provide a JSON description of available AOI Types,
        so that the client interface can be generic on changes.
    */
    public static function getDescription(){
        $desc = array();
        foreach(self::types() as $typeEnum => $label){
            $desc[$typeEnum] = array(
                'label' => $label,
                'hasText' => self::hasText($typeEnum)
            );
        }
        return $desc;
    }
}
