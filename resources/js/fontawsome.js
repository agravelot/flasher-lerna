import {dom, library} from '@fortawesome/fontawesome-svg-core'
// We are only using the wanted icons
import {faDownload, faEdit, faEnvelope, faHome, faKey, faUser, faSadTear} from '@fortawesome/free-solid-svg-icons'
import {faFacebookF, faInstagram} from '@fortawesome/free-brands-svg-icons'

library.add(
    faUser,
    faEnvelope,
    faKey,
    faHome,
    faDownload,
    faEdit,
    faSadTear,
);

library.add(
    faFacebookF,
    faInstagram,
);

// Replace any existing <i> tags with <svg> and set up a MutationObserver to
// continue doing this as the DOM changes.
dom.watch();
