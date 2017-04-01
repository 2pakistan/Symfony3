var $collectionHolder;

// setup an "add a file" link
var $addFileLink = $("<a href='#' class='btn btn-custom'>Add a file</a>");
var $newLinkLi = $('<li></li>').append($addFileLink);

jQuery(document).ready(function() {
    // Get the ul that holds the collection of medias
    $collectionHolder = $('ul.medias');

    // add the "add a tag" anchor and li to the medias ul
    $collectionHolder.append($newLinkLi);

    // add a delete link to all of the existing tag form li elements
    $collectionHolder.find('li').each(function() {
        addFileFormDeleteLink($(this));
    });

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
    var $newFormLi = $('<li></li>').append(newForm);
    $newLinkLi.before($newFormLi);

    // add a delete link to the new form
    addFileFormDeleteLink($newFormLi);
}

function addFileFormDeleteLink($tagFormLi) {
    var $removeFormA = $('<a href="#">delete this tag</a>');
    $tagFormLi.append($removeFormA);

    $removeFormA.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // remove the li for the tag form
        $tagFormLi.remove();
    });
}