const css = `
    .${classnames.container} {
        display: flex;
        border: 1px solid #CCCCCC;
    }

    .${classnames.aside} {
        flex: 0 0 auto;
        border-right: 1px solid #CCCCCC;
        background-color: #F5F5F5;
        padding: 8px;
    }

    .${classnames.body} {
        flex: 1 1 auto;
        overflow: auto;
    }

    .${classnames.grid} {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        grid-template-rows: repeat(24, 1fr);
        gap: 1px 1px;
    }

    .${classnames.header} {
        display: flex;
        flex-direction: row;
    }

    .${classnames.headerHour} {
        flex: 1 1 auto;
        text-align: center;
        border-bottom: 1px solid #CCCCCC;
        padding: 8px;
    }

    .${classnames.row} {
        display: flex;
        flex-direction: row;
    }

    .${classnames.day} {
        flex: 1 1 auto;
        background-color: #FFFFFF;
        border-bottom: 1px solid #CCCCCC;
        border-right: 1px solid #CCCCCC;
        cursor: pointer;
        user-select: none;
        text-align: center;
        padding: 8px;
    }

    .${classnames.hour} {
        flex: 1 1 auto;
        background-color: #FFFFFF;
        border-bottom: 1px solid #CCCCCC;
        cursor: pointer;
        user-select: none;
        text-align: center;
        padding: 8px;
    }

    .${classnames.hour}:not(:last-child) {
        border-right: 1px solid #CCCCCC;
    }

    .${classnames.selected} {
        background-color: #F5F5F5;
    }
`;

const library = (node, props = {}, callback, options = {}) => {
  const days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
  const hours = Array.from({ length: 24 }, (_, i) => i);

  const createNode = (tag, classNames = [], attributes = {}) => {
    const element = document.createElement(tag);
    element.classList.add(...classNames);
    Object.entries(attributes).forEach(([key, value]) => {
      element.setAttribute(key, value);
    });
    return element;
  };

  const handleHourClick = (event) => {
    const { target } = event;
    if (target.classList.contains(classnames.hour)) {
      target.classList.toggle(classnames.selected);
      if (callback) {
        const dayIndex = target.parentNode.getAttribute('data-day');
        const hour = target.getAttribute('data-hour');
        const selected = target.classList.contains(classnames.selected);
        callback(dayIndex, hour, selected);
      }
    }
  };

  const handleDayClick = (event) => {
    const { target } = event;
    if (target.classList.contains(classnames.day)) {
      target.classList.toggle(classnames.selected);
      if (callback) {
        const dayIndex = target.getAttribute('data-day');
        const selected = target.classList.contains(classnames.selected);
        const hourCells = target.parentNode.querySelectorAll(
          `.${classnames.row} > .${classnames.hour}`
        );
        Array.from(hourCells).forEach((cell) => {
          cell.classList[selected ? 'add' : 'remove'](classnames.selected);
          if (callback) {
            const hour = cell.getAttribute('data-hour');
            callback(dayIndex, hour, selected);
          }
        });
      }
    }
  };

  const render = () => {
    const style = createNode('style');
    style.innerHTML = css;
    const container = createNode('div', [classnames.container]);
    const aside = createNode('aside', [classnames.aside]);
    const body = createNode('div', [classnames.body]);
    const grid = createNode('div', [classnames.grid]);
    const header = createNode('div', [classnames.header]);
    days.forEach((day) => {
      const dayElement = createNode('div', [classnames.headerHour]);
      dayElement.textContent = day;
      header.appendChild(dayElement);
    });
    body.appendChild(header);
    hours.forEach((hour) => {
      const row = createNode('div', [classnames.row]);
      days.forEach((day, index) => {
        const hourCell = createNode('div', [classnames.hour]);
        hourCell.setAttribute('data-hour', hour);
        hourCell.setAttribute('data-day', index);
        row.appendChild(hourCell);
      });
      grid.appendChild(row);
    });
    body.appendChild(grid);
    container.appendChild(aside);
    container.appendChild(body);
    node.appendChild(style);
    node.appendChild(container);
    if (props.selected) {
      const selectedCells = container.querySelectorAll(
        `.${classnames.hour}.${classnames.selected}`
      );
      Array.from(selectedCells).forEach((cell) => {
        cell.classList.remove(classnames.selected);
      });
      props.selected.forEach(({ day, hour }) => {
        const cell = container.querySelector(
          `.${classnames.hour}[data-day="${day}"][data-hour="${hour}"]`
        );
        if (cell) {
          cell.classList.add(classnames.selected);
        }
      });
    }
    container.addEventListener('click', handleHourClick);
    container.addEventListener('click', handleDayClick);
  };

  render();
};

export default library;
