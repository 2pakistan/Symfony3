var $collectionHolder;

// setup an "add a file" link
var $addFileLink = $("<a href='#' class='btn-circle btn-custom btn-block btn-lg text-center'><span class='glyphicon glyphicon-camera'></span></a>");
var $newLinkLi = $('<li class="col-sm-2 media-list"></li>').append($addFileLink);

jQuery(document).ready(function() {
    // Get the ul that holds the collection of medias
    $collectionHolder = $('ul.medias');

    // add the "add a tag" anchor and li to the medias ul
    $collectionHolder.append($newLinkLi);



    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find(':input').length);

    $addFileLink.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new tag form (see next code block)
        addFileForm($collectionHolder, $newLinkLi);
    });
});

function addFileForm($collectionHolder, $newLinkLi) {
    // Get the data-prototype explained earlie r
    var prototype = $collectionHolder.data('prototype');

    // get the new index
    var index = $collectionHolder.data('index');

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    var newForm = prototype.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a tag" link li
    var $newFormLi = $('<div class="col-sm-8"><li class="media-list "></li></div>').append(newForm);
    $newLinkLi.before($newFormLi);

    // add a delete link to the new form
    addFileFormDeleteLink($newFormLi);
}

function addFileFormDeleteLink($tagFormLi) {
    var $removeFormA = $('<a href="#" class="col-sm-2 btn-alert btn-lg text-center"><span class="glyphicon glyphicon-remove"></span></a>');
    $tagFormLi.append($removeFormA);

    $removeFormA.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // remove the li for the tag form
        $tagFormLi.remove();
    });
}