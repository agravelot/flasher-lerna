import { dom, library } from '@fortawesome/fontawesome-svg-core';
import { faBell, faEdit, faStickyNote } from '@fortawesome/free-regular-svg-icons';
// We are only using the wanted icons
import {
    faAngleDown,
    faAngleLeft,
    faAngleRight,
    faArrowUp,
    faBook,
    faCheck,
    faChevronCircleLeft,
    faCog,
    faExclamationCircle,
    faExternalLinkAlt,
    faImages,
    faInfo,
    faLock,
    faPenFancy,
    faPlus,
    faSearch,
    faSignInAlt,
    faTachometerAlt,
    faTag,
    faTags,
    faTasks,
    faTrash,
    faTrashAlt,
    faUpload,
    faUserTag,
    faLink, faEye,
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
    faCog,
    faExclamationCircle,
    faBell,
    faStickyNote,
    faEdit,
    faLink,
    faEye
);

// Replace any existing <i> tags with <svg> and set up a MutationObserver to
// continue doing this as the DOM changes.
dom.watch();
