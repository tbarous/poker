import React from "react";
import {FunctionComponent, ReactElement} from "react";
import styled from "styled-components";

const Wrapper = styled.div`
    display: flex;
    align-items: center;
    justify-content: center;
    background: #2d3748;
    border-bottom-left-radius: 4px;
    border-bottom-right-radius: 4px;
`;

const PaginationButton = styled.button<{ active: boolean }>`
    background: ${p => p.active ? 'lightgreen' : (p.disabled ? "lightgrey" : "white")};
    border: none;
    border-radius: 4px;
    cursor: ${p => p.disabled ? "auto" : "pointer"};
    padding: .5rem 1rem;
    margin: 1rem;
    min-width: 46px;
    text-decoration: none;
    font-weight: normal;
    font-size: 11px;
    color: ${p => p.active ? 'black' : (p.disabled ? "grey" : "#333")};
    font-family: 'Nunito', sans-serif;
`;

interface Props {
    links: any,
    currentPage: number,
    numberOfPages: number,
    onClick: (obj: any) => void
}

interface Link {
    url: string,
    label: string,
    active: boolean
}

const Pagination: FunctionComponent<Props> = (props: Props): ReactElement => {
    const {links, currentPage, numberOfPages, onClick} = props;

    function click(link: Link) {
        onClick([{prop: "page", value: link.url.split("=")[1]}])
    }

    function getLabel(label) {
        if (label === 'pagination.previous') {
            return '<'
        }

        if (label === 'pagination.next') {
            return '>'
        }

        return label;
    }

    return (
        <Wrapper>
            {links.map((link: any, index: number) => {
                return (
                    <PaginationButton
                        disabled={!link.url}
                        dangerouslySetInnerHTML={{__html: getLabel(link.label)}}
                        onClick={() => click(link)}
                        key={index}
                        active={link.active}
                    />
                )
            })}
        </Wrapper>
    )
}

export default Pagination;
