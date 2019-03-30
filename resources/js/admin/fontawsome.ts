import { dom, library } from '@fortawesome/fontawesome-svg-core';
import { faBell, faEdit, faStickyNote } from '@fortawesome/free-regular-svg-icons';
// We are only using the wanted icons
import {
    faAngleDown,
    faBook,
    faCheck,
    faChevronCircleLeft,
    faImages,
    faLock,
    faPenFancy,
    faPlus,
    faSearch,
    faTachometerAlt,
    faTags,
    faTasks,
    faUserTag,
    faTrashAlt,
    faExternalLinkAlt,
    faUpload,
    faSignInAlt,
    faAngleRight,
    faAngleLeft,
    faArrowUp,
    faTag,
    faInfo,
    faCog,
} from '@fortawesome/free-solid-svg-icons';

library.add(
    faImages,
    faTags,
    faUserTag,
    faPenFancy,
    faBook,
    faTachometerAlt,
    faTasks,
    faChevronCircleLeft,
    faAngleDown,
    faCheck,
    faSearch,
    faPlus,
    faLock,
    faTrashAlt,
    faExternalLinkAlt,
    faUpload,
    faSignInAlt,
    faAngleRight,
    faAngleLeft,
    faArrowUp,
    faTag,
    faInfo,
    faCog
);

library.add(faBell, faStickyNote, faEdit, faTrashAlt);

// Replace any existing <i> tags with <svg> and set up a MutationObserver to
// continue doing this as the DOM changes.
dom.watch();
