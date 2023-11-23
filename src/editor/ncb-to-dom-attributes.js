const ncb_to_dom_attributes = ( attributes) => {
  let attr_str = '';

  for (let [attr_key, attr_value] of Object.entries( attributes )) {
    if (typeof attr_value === 'object') {
      attr_value = attr_value.join( ' ' );
    }

    attr_str += ` ${attr_key}="${attr_value}"`;
  }

  return attr_str.trim();
}

export default ncb_to_dom_attributes;
