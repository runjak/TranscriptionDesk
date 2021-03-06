/**
    This module tries to isolate 'tags' from the descriptions
    of mufi symbols given in mufiSymbols.js.
    It than provides functionality to filter symbols by tags.
    To obtain tags we split the descriptions by their white space.
    We guess that tags follow a pareto distribution and so it may be enough
    to consider the 20% of tags with the most entries.
    To realize this we will sort tags by their number of entries,
    and provide the means to cut them off somewhere.
*/
define(['mufiSymbols'], function(mufiSymbols){
    /**
        tags is a map of tag names to arrays of mufi symbol hex strings.
    */
    var tags = {};
    //Filling tags:
    for(var categoryKey in mufiSymbols){
        var category = mufiSymbols[categoryKey];
        for(var symbol in category){
            //var description = category[symbol]
            var parts = category[symbol].split(' ');
            parts.forEach(function(tag){
                if(tag in tags){
                    tags[tag].push(symbol);
                }else{
                    tags[tag] = [symbol];
                }
            });
        }
    }
    //Sorting tags by number of symbols DESC:
    var sortedTags = Object.keys(tags).sort(function(x, y){
        var xScore = tags[x].length
          , yScore = tags[y].length;
        return (xScore - yScore) * -1;
    });
    /*
      @param [split number]
      @return tags [String]
      The paretoTags function only returns the percentage
      of tags with the highest number of symbols in them.
      The split parameter may be omitted and defaults to 0.2.
    */
    var paretoTags = function(split){
        //split defaults to 0.2 for pareto madness.
        var defaultSplit = 0.2;
        //Sanitizing split:
        split = (typeof(split) === 'number') ? split : defaultSplit;
        //Making sure split !== NaN:
        if(Number.isNaN(split)){ split = defaultSplit; }
        //Making sure split in [0,1]:
        split = Math.max(0, Math.min(1, split));
        //Calculating number of elements to be returned:
        var count = Math.ceil(sortedTags.length * split);
        //Slicing sortedTags:
        return sortedTags.slice(0, count);
    };
    /**
        @param tgs [String]
        @return symbols [String]
        Returns an array of symbols that are present in all given tags.
        If no arguments are given an empty array is returned.
    */
    var intersectTags = function(tgs){
        var set = null;//Mapps symbols to null, a simple memo.
        /*
            Iterating arguments:
            See [1] for reason why some is used here instead of forEach.
            [1]: https://stackoverflow.com/a/2641374/448591
        */
        tgs.some(function(tag){
            //Checking if set is still null:
            if(set === null){
                set = {};//Init set.
                //Adding all symbols for tag to set:
                tags[tag].forEach(function(symbol){ set[symbol] = null; });
            }else if(Object.keys(set).length === 0){
                //Short circuit some…
                console.log(set);
                return true;
            }else{
                //Intersecting:
                var newSet = {};
                tags[tag].forEach(function(symbol){
                    if(symbol in set){
                        newSet[symbol] = null;
                    }
                });
                set = newSet;
            }
            //Need to traverse further:
            return false;
        });
        //Returning tags:
        return (set === null) ? [] : Object.keys(set);
    }
    /**
        @param minuend [String]
        @param subtrahend [String]
        @return tags [String]
        Computes the set difference between two arrays of Strings.
    */
    var otherTags = function(minuend, subtrahend){
        var filterSet = {};
        subtrahend.forEach(function(tag){
            filterSet[tag] = null;
        });
        var tags = [];
        minuend.forEach(function(tag){
            if(!(tag in filterSet)){
                tags.push(tag);
            }
        });
        return tags;
    };
    /**
        @param givenTags [String]
        @return tags [String]
        Returns an array of tags that could be added to the given tags,
        so that the resulting set of tags would still
        result in symbols via otherTags.
    */
    var remainingTags = function(givenTags){
        if(givenTags.length === 0){ return sortedTags; }
        var tags = [];
        var others = otherTags(sortedTags, givenTags);
        others.forEach(function(tag){
            var toTest = givenTags.slice(0);
            toTest.push(tag);
            if(intersectTags(toTest).length > 0){
                tags.push(tag);
            }
        });
        return tags;
    };
    //Exporting stuff:
    return {
        tags: tags
    ,   sortedTags: sortedTags
    ,   paretoTags: paretoTags
    ,   intersectTags: intersectTags
    ,   otherTags: otherTags
    ,   remainingTags: remainingTags
    };
});
