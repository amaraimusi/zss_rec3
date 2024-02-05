// ToggleButton.tsx
import React, { useState } from 'react';

type ToggleButtonProps = {
  targetId: string;
  label: string;
};

const ToggleButton: React.FC<ToggleButtonProps> = ({ targetId, label }) => {
  const [isVisible, setIsVisible] = useState<boolean>(false);

  const toggleVisibility = () => {
    const element = document.getElementById(targetId);
    if (element) {
      if (isVisible) {
        element.style.display = 'none';
      } else {
        element.style.display = 'block';
      }
      setIsVisible(!isVisible);
    }
  };

  return <button onClick={toggleVisibility} class="btn btn-info">{isVisible ? `${label}(閉じる)` : `${label}`}</button>;
};

export default ToggleButton;